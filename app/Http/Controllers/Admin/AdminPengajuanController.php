<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPa;
use App\Models\TitleReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPengajuanController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan judul proyek akhir
     */
    public function index(): View
    {
        // Eager Loading relasi user (beserta student dan finalProject.titleReview) dan child table untuk mencegah N+1 Query
        $pengajuans = PengajuanPa::with([
            'user.student.finalProject.titleReview.reviewer',
            'detail_perancangan',
            'detail_implementasi',
            'detail_prestasi'
        ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('user_id')
            ->values();

        return view('admin.titles.index', compact('pengajuans'));
    }

    /**
     * Menampilkan detail lengkap satu pengajuan
     */
    public function show(int|string $id): View
    {
        $pengajuan = PengajuanPa::with([
            'user.student.finalProject.titleReview.reviewer',
            'detail_perancangan',
            'detail_implementasi',
            'detail_prestasi'
        ])
            ->findOrFail($id);

        return view('admin.titles.show', compact('pengajuan'));
    }

    /**
     * Memperbarui status dan penilaian pengajuan (disetujui, ditolak, revisi, pending)
     */
    public function updateStatus(Request $request, int|string $id): RedirectResponse
    {
        $pengajuan = PengajuanPa::with(['user.student', 'detailPerancangan', 'detailImplementasi', 'detailPrestasi'])
            ->findOrFail($id);

        $isRubricScheme = in_array($pengajuan->jenis_skema, ['perancangan', 'implementasi'], true);

        $rules = [
            'status_pengajuan' => ['required', 'in:pending,approved,rejected,revision'],
            'catatan_review' => ['nullable', 'string', 'max:1000'],
        ];

        if ($isRubricScheme && $request->has('problem_score')) {
            $rules['problem_score'] = ['required', 'integer', 'between:0,10'];
            $rules['solution_score'] = ['required', 'integer', 'between:0,10'];
            $rules['complexity_score'] = ['required', 'integer', 'between:0,10'];
            $rules['method_score'] = ['required', 'integer', 'between:0,10'];
            $rules['testing_score'] = ['required', 'integer', 'between:0,10'];
        }

        $validated = $request->validate($rules);

        $pengajuan->update([
            'status_pengajuan' => $validated['status_pengajuan'],
            'catatan_review' => $validated['catatan_review'] ?? null,
        ]);

        // Sinkronisasi dengan tabel final_projects
        $finalProject = $pengajuan->syncToFinalProject();

        // Simpan penilaian rubrik jika skema perancangan/implementasi dan nilai skor diisi
        if ($isRubricScheme && $request->has('problem_score') && $finalProject) {
            $scores = [
                'problem_score' => (int) $validated['problem_score'],
                'solution_score' => (int) $validated['solution_score'],
                'complexity_score' => (int) $validated['complexity_score'],
                'method_score' => (int) $validated['method_score'],
                'testing_score' => (int) $validated['testing_score'],
            ];
            $totalScore = array_sum($scores);

            $decision = match ($validated['status_pengajuan']) {
                'approved' => 'APPROVED',
                'rejected' => 'REJECTED',
                default => 'REVISION',
            };

            TitleReview::updateOrCreate(
                ['final_project_id' => $finalProject->id],
                array_merge($scores, [
                    'reviewer_id' => $request->user()->id,
                    'total_score' => $totalScore,
                    'decision' => $decision,
                    'comment' => $validated['catatan_review'] ?? null,
                ])
            );
        }

        return redirect()
            ->back()
            ->with('success', 'Penilaian dan status pengajuan berhasil disimpan (Status: ' . strtoupper($validated['status_pengajuan']) . ').');
    }
}
