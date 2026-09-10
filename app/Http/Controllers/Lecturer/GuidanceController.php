<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewDefenseApprovalRequest;
use App\Http\Requests\ReviewProgressLogRequest;
use App\Models\FinalProject;
use App\Models\ProgressLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GuidanceController extends Controller
{
    public function index(Request $request): View
    {
        $lecturer = $request->user()->lecturer;
        $projects = $lecturer?->supervisors()
            ->with('finalProject.student', 'finalProject.progressLogs')
            ->get()
            ->pluck('finalProject')
            ->filter()
            ->unique('id')
            ->values() ?? collect();

        return view('lecturer.guidances.index', compact('projects'));
    }

    public function show(Request $request, FinalProject $finalProject): View
    {
        $this->authorizeLecturerProject($request, $finalProject);
        $finalProject->load('student', 'supervisors.lecturer', 'progressLogs.comments.user');

        return view('lecturer.guidances.show', compact('finalProject'));
    }

    public function review(Request $request, ReviewProgressLogRequest $reviewRequest, ProgressLog $progressLog): RedirectResponse
    {
        $this->authorizeLecturerProject($request, $progressLog->finalProject);

        $data = $reviewRequest->validated();

        if ($reviewRequest->hasFile('review_file')) {
            $data['revision_file_path'] = $reviewRequest->file('review_file')->store('guidance/revisions', 'public');
        }

        $progressLog->update($data);

        return back()->with('success', 'Review progress berhasil disimpan.');
    }

    public function approveDefense(Request $request, ReviewDefenseApprovalRequest $approvalRequest, FinalProject $finalProject): RedirectResponse
    {
        $this->authorizeLecturerProject($request, $finalProject);

        $lecturerId = $request->user()->lecturer?->id;
        $approvalColumn = null;

        if ($finalProject->supervisorOne()?->lecturer_id === $lecturerId) {
            $approvalColumn = 'supervisor_one_approval';
        } elseif ($finalProject->supervisorTwo()?->lecturer_id === $lecturerId) {
            $approvalColumn = 'supervisor_two_approval';
        }

        abort_unless($approvalColumn, 403, 'Anda tidak berhak menyetujui sidang untuk mahasiswa ini.');

        $finalProject->update([$approvalColumn => $approvalRequest->input('approval_status')]);

        if ($finalProject->supervisor_one_approval === 'APPROVED' && $finalProject->supervisor_two_approval === 'APPROVED') {
            $finalProject->update(['status' => 'READY_FOR_DEFENSE']);
        } elseif ($finalProject->status === 'READY_FOR_DEFENSE') {
            $finalProject->update(['status' => 'APPROVED']);
        }

        return back()->with('success', 'Status approval sidang berhasil disimpan.');
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
