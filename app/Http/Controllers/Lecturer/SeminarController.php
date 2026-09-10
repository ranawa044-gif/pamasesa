<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewSeminarProposalRequest;
use App\Models\FinalProject;
use App\Models\SeminarProposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeminarController extends Controller
{
    public function index(Request $request): View
    {
        $lecturer = $request->user()->lecturer;
        $projects = $lecturer?->supervisors()
            ->with('finalProject.seminarProposal')
            ->get()
            ->pluck('finalProject')
            ->filter()
            ->unique('id')
            ->values() ?? collect();

        return view('lecturer.seminars.index', compact('projects'));
    }

    public function show(Request $request, SeminarProposal $seminarProposal): View
    {
        $this->authorizeLecturerProject($request, $seminarProposal->finalProject);
        $seminarProposal->load('finalProject.student', 'finalProject.progressLogs', 'schedule');

        return view('lecturer.seminars.show', compact('seminarProposal'));
    }

    public function review(Request $request, ReviewSeminarProposalRequest $reviewRequest, SeminarProposal $seminarProposal): RedirectResponse
    {
        $this->authorizeLecturerProject($request, $seminarProposal->finalProject);

        abort_unless(
            in_array($seminarProposal->status, ['WAITING_APPROVAL', 'REVISION'], true),
            403,
            'Seminar tidak dalam status review.'
        );

        $lecturerId = $request->user()->lecturer->id;

        if ($seminarProposal->finalProject->supervisorOne()?->lecturer_id === $lecturerId) {
            $seminarProposal->update([
                'supervisor_one_approval' => $reviewRequest->input('approval'),
                'supervisor_one_note' => $reviewRequest->input('note'),
            ]);
        } elseif ($seminarProposal->finalProject->supervisorTwo()?->lecturer_id === $lecturerId) {
            $seminarProposal->update([
                'supervisor_two_approval' => $reviewRequest->input('approval'),
                'supervisor_two_note' => $reviewRequest->input('note'),
            ]);
        }

        if ($seminarProposal->supervisor_one_approval === 'REVISION' || $seminarProposal->supervisor_two_approval === 'REVISION') {
            $seminarProposal->update(['status' => 'REVISION']);
        } elseif ($seminarProposal->readyForSeminar()) {
            $seminarProposal->update(['status' => 'APPROVED_BY_SUPERVISORS']);
        } else {
            $seminarProposal->update(['status' => 'WAITING_APPROVAL']);
        }

        return back()->with('success', 'Review seminar berhasil disimpan.');
    }

    private function authorizeLecturerProject(Request $request, FinalProject $finalProject): void
    {
        $lecturerId = $request->user()->lecturer?->id;

        abort_unless(
            $lecturerId && $finalProject->supervisors()->where('lecturer_id', $lecturerId)->exists(),
            403,
            'Anda bukan pembimbing mahasiswa ini.'
        );
    }
}
