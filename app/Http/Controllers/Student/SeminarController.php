<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeminarProposalRequest;
use App\Http\Requests\SubmitSeminarRevisionRequest;
use App\Models\FinalProject;
use App\Models\SeminarProposal;
use App\Models\SeminarRevision;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SeminarController extends Controller
{
    public function index(Request $request): View
    {
        $finalProject = $request->user()->student?->finalProject()->with([
            'seminarProposal.schedule.room',
            'seminarProposal.schedule.assessments.lecturer',
            'seminarProposal.schedule.revisions.lecturer',
        ])->first();

        $pengajuan = $request->user()->pengajuanPa ?? $finalProject?->student?->user?->pengajuanPa;

        return view('student.seminar.index', compact('finalProject', 'pengajuan'));
    }

    public function create(Request $request): View
    {
        $finalProject = $request->user()->student?->finalProject;
        $pengajuan = $request->user()->pengajuanPa ?? $finalProject?->student?->user?->pengajuanPa;

        abort_unless($finalProject && $finalProject->status === 'APPROVED', 403, 'Judul harus ACC terlebih dahulu.');
        abort_unless($pengajuan && $pengajuan->is_acc_p1 && $pengajuan->is_acc_p2, 403, 'Kedua dosen pembimbing harus memberikan ACC terlebih dahulu.');
        abort_unless($finalProject->supervisorOne() && $finalProject->supervisorTwo(), 403, 'Pembimbing harus sudah ditentukan.');

        return view('student.seminar.form', compact('finalProject', 'pengajuan'));
    }

    public function store(StoreSeminarProposalRequest $request): RedirectResponse
    {
        $finalProject = FinalProject::findOrFail($request->input('final_project_id'));
        $pengajuan = $finalProject->student?->user?->pengajuanPa;

        abort_unless($finalProject->status === 'APPROVED', 403, 'Judul harus ACC terlebih dahulu.');
        abort_unless($pengajuan && $pengajuan->is_acc_p1 && $pengajuan->is_acc_p2, 403, 'Kedua dosen pembimbing harus memberikan ACC terlebih dahulu.');
        abort_unless($finalProject->supervisorOne() && $finalProject->supervisorTwo(), 403, 'Pembimbing harus sudah ditentukan.');

        $file = $request->file('proposal_file')->store('seminars/proposals', 'public');

        $proposal = SeminarProposal::updateOrCreate(
            ['final_project_id' => $finalProject->id],
            [
                'status' => 'WAITING_APPROVAL',
                'proposal_file' => $file,
                'submitted_at' => now(),
                'student_note' => $request->input('student_note'),
            ]
        );

        return redirect()->route('student.seminars.index')->with('success', 'Pengajuan seminar berhasil dikirim.');
    }

    public function completeRevision(SubmitSeminarRevisionRequest $request, SeminarRevision $revision): RedirectResponse
    {
        $student = $request->user()->student;
        abort_unless($student && $revision->seminarSchedule->seminarProposal->finalProject->student_id === $student->id, 403, 'Anda tidak dapat mengubah revisi ini.');
        abort_unless(in_array($revision->status, ['OPEN', 'REVISION'], true), 403, 'Revisi ini tidak dapat dikirimkan kembali.');

        $data = [
            'student_note' => $request->input('student_note'),
            'status' => 'WAITING_VALIDATION',
        ];

        if ($request->hasFile('revision_file')) {
            $data['student_file_path'] = $request->file('revision_file')->store('seminar/revisions', 'public');
        }

        $revision->update($data);

        return back()->with('success', 'Bukti revisi berhasil dikirim. Tunggu validasi pembimbing.');
    }
}
