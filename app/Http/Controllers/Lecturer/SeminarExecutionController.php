<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewSeminarRevisionRequest;
use App\Http\Requests\StoreSeminarAssessmentRequest;
use App\Http\Requests\StoreSeminarRevisionRequest;
use App\Models\SeminarAssessment;
use App\Models\SeminarRevision;
use App\Models\SeminarSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeminarExecutionController extends Controller
{
    public function show(Request $request, SeminarSchedule $seminarSchedule): View
    {
        $lecturer = $request->user()->lecturer;
        $this->authorizeLecturer($lecturer?->id, $seminarSchedule);

        $seminarSchedule->load(['seminarProposal.finalProject.student', 'seminarProposal.finalProject.supervisors.lecturer', 'room', 'assessments.lecturer', 'revisions.lecturer']);

        $assessment = $seminarSchedule->assessments()->where('lecturer_id', $lecturer->id)->first();
        $finalDecision = $this->calculateFinalDecision($seminarSchedule);

        return view('lecturer.seminars.start', [
            'seminarSchedule' => $seminarSchedule,
            'assessment' => $assessment,
            'finalDecision' => $finalDecision,
        ]);
    }

    public function assess(StoreSeminarAssessmentRequest $request, Request $httpRequest, SeminarSchedule $seminarSchedule): RedirectResponse
    {
        $lecturer = $httpRequest->user()->lecturer;
        $this->authorizeLecturer($lecturer?->id, $seminarSchedule);

        abort_unless(in_array($seminarSchedule->status, ['PUBLISHED', 'ONGOING'], true), 403, 'Seminar belum dapat dinilai.');

        $data = $request->validated();
        $total = round(((
            $data['problem_score'] +
            $data['method_score'] +
            $data['design_score'] +
            $data['implementation_score'] +
            $data['presentation_score']
        ) / 25) * 100);

        SeminarAssessment::updateOrCreate([
            'seminar_schedule_id' => $seminarSchedule->id,
            'lecturer_id' => $lecturer->id,
        ], [
            'problem_score' => $data['problem_score'],
            'method_score' => $data['method_score'],
            'design_score' => $data['design_score'],
            'implementation_score' => $data['implementation_score'],
            'presentation_score' => $data['presentation_score'],
            'total_score' => $total,
            'decision' => $data['decision'],
            'revision_note' => $data['revision_note'] ?? null,
            'assessed_at' => now(),
        ]);

        if ($seminarSchedule->status === 'PUBLISHED') {
            $seminarSchedule->update(['status' => 'ONGOING']);
        }

        $this->finalizeSeminar($seminarSchedule);

        return back()->with('success', 'Penilaian seminar tersimpan.');
    }

    public function revision(StoreSeminarRevisionRequest $request, Request $httpRequest, SeminarSchedule $seminarSchedule): RedirectResponse
    {
        $lecturer = $httpRequest->user()->lecturer;
        $this->authorizeLecturer($lecturer?->id, $seminarSchedule);

        abort_unless(in_array($seminarSchedule->status, ['PUBLISHED', 'ONGOING'], true), 403, 'Seminar belum dapat direvisi.');

        $seminarSchedule->revisions()->create([
            'lecturer_id' => $lecturer->id,
            'revision_category' => $request->input('revision_category'),
            'revision_note' => $request->input('revision_note'),
            'status' => 'OPEN',
        ]);

        if ($seminarSchedule->status === 'PUBLISHED') {
            $seminarSchedule->update(['status' => 'ONGOING']);
        }

        return back()->with('success', 'Catatan revisi berhasil ditambahkan.');
    }

    public function reviewRevision(ReviewSeminarRevisionRequest $request, SeminarRevision $revision): RedirectResponse
    {
        $lecturer = $request->user()->lecturer;
        $this->authorizeLecturer($lecturer?->id, $revision->seminarSchedule);

        abort_unless($revision->status === 'WAITING_VALIDATION', 403, 'Revisi belum siap untuk divalidasi.');

        $revision->update([
            'status' => $request->input('status'),
            'validation_note' => $request->input('validation_note'),
        ]);

        return back()->with('success', 'Validasi revisi berhasil disimpan.');
    }

    private function authorizeLecturer(?int $lecturerId, SeminarSchedule $seminarSchedule): void
    {
        abort_unless($lecturerId && $seminarSchedule->seminarProposal->finalProject->supervisors()->where('lecturer_id', $lecturerId)->exists(), 403, 'Anda bukan pembimbing seminar ini.');
    }

    private function calculateFinalDecision(SeminarSchedule $seminarSchedule): ?string
    {
        $decisions = $seminarSchedule->assessments->pluck('decision')->all();

        if (count($decisions) < 2) {
            return null;
        }

        if (in_array('REPEAT', $decisions, true)) {
            return 'REPEAT';
        }

        if (in_array('PASSED_WITH_REVISION', $decisions, true)) {
            return 'PASSED_WITH_REVISION';
        }

        return 'PASSED';
    }

    private function finalizeSeminar(SeminarSchedule $seminarSchedule): void
    {
        $schedule = $seminarSchedule->load('assessments');

        if ($schedule->assessments->count() < 2) {
            return;
        }

        $decisions = $schedule->assessments->pluck('decision')->all();
        if (in_array('REPEAT', $decisions, true)) {
            $schedule->seminarProposal->update(['status' => 'SEMINAR_REPEAT']);
        } else {
            $schedule->seminarProposal->update(['status' => 'SEMINAR_PASSED']);
        }

        $schedule->update(['status' => 'FINISHED']);
    }
}
