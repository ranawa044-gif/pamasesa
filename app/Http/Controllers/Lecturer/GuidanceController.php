<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewDefenseApprovalRequest;
use App\Http\Requests\ReviewProgressLogRequest;
use App\Models\FinalProject;
use App\Models\PengajuanPa;
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
        $finalProject->load('student.user.pengajuanPa', 'supervisors.lecturer', 'progressLogs.comments.user');
        $pengajuanPa = $finalProject->student?->user?->pengajuanPa;

        return view('lecturer.guidances.show', compact('finalProject', 'pengajuanPa'));
    }

    public function review(Request $request, ReviewProgressLogRequest $reviewRequest, ProgressLog $progressLog): RedirectResponse
    {
        $this->authorizeLecturerProject($request, $progressLog->finalProject);

        $data = $reviewRequest->validated();

        if ($reviewRequest->hasFile('review_file')) {
            $data['revision_file_path'] = $reviewRequest->file('review_file')->store('guidance/revisions', 'public');
        }

        // Otomatis ubah status menjadi verified saat dosen memverifikasi progress
        $data['status'] = 'verified';

        $progressLog->update($data);

        // Jika terdapat balasan komentar/catatan, catat juga ke riwayat diskusi bimbingan
        if (!empty($data['review_note'])) {
            $progressLog->comments()->create([
                'user_id' => $request->user()->id,
                'comment' => $data['review_note'],
            ]);
        }

        return back()->with('success', 'Progress bimbingan berhasil diverifikasi dan balasan komentar tersimpan.');
    }

    public function accSeminar(Request $request, FinalProject $finalProject): RedirectResponse
    {
        $this->authorizeLecturerProject($request, $finalProject);

        $lecturer = $request->user()->lecturer;
        $lecturerId = $lecturer?->id;
        $student = $finalProject->student;
        $user = $student?->user;

        abort_unless($user, 404, 'Data user mahasiswa tidak ditemukan.');

        $pengajuanPa = $user->pengajuanPa;
        if (!$pengajuanPa) {
            $pengajuanPa = PengajuanPa::where('user_id', $user->id)->latest()->first();
        }

        if (!$pengajuanPa) {
            $pengajuanPa = PengajuanPa::create([
                'user_id' => $user->id,
                'jenis_skema' => 'implementasi',
                'status_pengajuan' => 'approved',
                'is_acc_p1' => false,
                'is_acc_p2' => false,
            ]);
        }

        $isP1 = ($finalProject->supervisorOne()?->lecturer_id === $lecturerId);
        $isP2 = ($finalProject->supervisorTwo()?->lecturer_id === $lecturerId);

        abort_unless($isP1 || $isP2, 403, 'Anda bukan pembimbing 1 maupun pembimbing 2 dari pengajuan ini.');

        $action = $request->input('action', 'acc');
        $supervisorTitle = $isP1 ? 'Pembimbing 1' : 'Pembimbing 2';
        $supervisorName = $lecturer?->nama ?? 'Dosen Pembimbing';

        if ($action === 'cancel') {
            if ($isP1) {
                $pengajuanPa->is_acc_p1 = false;
            }
            if ($isP2) {
                $pengajuanPa->is_acc_p2 = false;
            }
            $pengajuanPa->status_pengajuan = 'approved';
            $pengajuanPa->catatan_review = "Persetujuan ACC Seminar dibatalkan oleh {$supervisorTitle} ({$supervisorName}) pada " . now()->translatedFormat('d F Y H:i') . '.';
            $pengajuanPa->save();

            return back()->with('info', "Persetujuan ACC Seminar dari {$supervisorTitle} berhasil dibatalkan.");
        }

        // Aksi ACC: update is_acc_p1 jika pembimbing 1, is_acc_p2 jika pembimbing 2
        if ($isP1) {
            $pengajuanPa->is_acc_p1 = true;
        }
        if ($isP2) {
            $pengajuanPa->is_acc_p2 = true;
        }

        // Cek apakah KEDUA pembimbing telah memberikan ACC
        $bothAcc = $pengajuanPa->is_acc_p1 && $pengajuanPa->is_acc_p2;

        if ($bothAcc) {
            $pengajuanPa->status_pengajuan = 'acc_seminar';
            $pengajuanPa->catatan_review = "KEDUA Dosen Pembimbing telah memberikan ACC Seminar Proposal pada " . now()->translatedFormat('d F Y H:i') . '. Mahasiswa memenuhi syarat untuk mendaftar Seminar Proposal.';
        } else {
            $pengajuanPa->catatan_review = "ACC Seminar Proposal diberikan oleh {$supervisorTitle} ({$supervisorName}) pada " . now()->translatedFormat('d F Y H:i') . '. Menunggu persetujuan pembimbing lainnya.';
        }
        $pengajuanPa->save();

        // Pastikan status final_project minimal APPROVED
        if (!in_array($finalProject->status, ['APPROVED', 'READY_FOR_DEFENSE'], true)) {
            $finalProject->update(['status' => 'APPROVED']);
        }

        $message = $bothAcc
            ? "Berhasil! KEDUA dosen pembimbing telah memberikan ACC. Mahasiswa sekarang berhak mendaftar Seminar Proposal."
            : "Persetujuan ACC Seminar dari {$supervisorTitle} berhasil disimpan. Menunggu ACC dari pembimbing lainnya.";

        return back()->with('success', $message);
    }

    public function accSeminarProposal(Request $request, FinalProject $finalProject): RedirectResponse
    {
        return $this->accSeminar($request, $finalProject);
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
