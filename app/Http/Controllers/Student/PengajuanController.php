<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DetailImplementasi;
use App\Models\DetailPerancangan;
use App\Models\DetailPrestasi;
use App\Models\PengajuanPa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanController extends Controller
{
    public function create(Request $request): View
    {
        $user = $request->user();
        abort_if(strtoupper($user->role) !== 'MAHASISWA', 403, 'Akses khusus mahasiswa.');

        $student = $user->student;
        $pengajuan = PengajuanPa::with(['user.student.finalProject.titleReview', 'detailPerancangan', 'detailImplementasi', 'detailPrestasi'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $lockedSubmission = in_array($pengajuan?->status_pengajuan, ['pending', 'review', 'approved', 'acc_seminar'], true);

        return view('student.pengajuan.create', compact('user', 'student', 'pengajuan', 'lockedSubmission'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Pengecekan RBAC: Hanya role MAHASISWA yang bisa submit
        abort_if(strtoupper($user->role) !== 'MAHASISWA', 403, 'Akses ditolak. Hanya mahasiswa yang dapat melakukan pengajuan.');

        // Cegah pengajuan ganda jika sedang diproses atau sudah disetujui
        $existing = PengajuanPa::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($existing && in_array($existing->status_pengajuan, ['pending', 'review', 'approved', 'acc_seminar'], true)) {
            return redirect()
                ->route('student.pengajuan.create')
                ->withErrors(['error' => 'Pengajuan judul sedang diproses atau sudah disetujui, tidak dapat mengajukan ulang.']);
        }

        // 2. Rule Validasi
        $rules = [
            'phone' => ['required', 'string', 'max:20'],
            'guardian_phone' => ['required', 'string', 'max:20'],
            'jenis_skema' => ['required', 'in:perancangan,implementasi,prestasi'],
        ];

        if ($request->jenis_skema === 'perancangan') {
            $rules += [
                'perancangan_judul_pa' => ['required', 'string', 'max:255'],
                'perancangan_lokasi_penelitian' => ['required', 'string', 'max:255'],
                'perancangan_pendahuluan' => ['required', 'string'],
                'perancangan_dosen_pembimbing' => ['required', 'string', 'max:255'],
                'perancangan_proses_bisnis' => ['required', 'string'],
                'perancangan_jenis_sistem' => ['required', 'string', 'max:100'],
                'perancangan_jumlah_aktor' => ['required', 'integer', 'min:1'],
                'perancangan_fitur_utama' => ['required', 'string'],
                'perancangan_metode_pengembangan' => ['required', 'string', 'max:100'],
                'perancangan_metode_pendekatan' => ['required', 'array', 'min:1'],
                'perancangan_tools_perancangan' => ['required', 'string', 'max:255'],
            ];
        } elseif ($request->jenis_skema === 'implementasi') {
            $rules += [
                'implementasi_judul_pa' => ['required', 'string', 'max:255'],
                'implementasi_lokasi_penelitian' => ['required', 'string', 'max:255'],
                'implementasi_pendahuluan' => ['required', 'string'],
                'implementasi_dosen_pembimbing' => ['required', 'string', 'max:255'],
                'implementasi_proses_bisnis' => ['required', 'string'],
                'implementasi_jenis_sistem' => ['required', 'string', 'max:100'],
                'implementasi_jumlah_aktor' => ['required', 'integer', 'min:1'],
                'implementasi_fitur_utama' => ['required', 'string'],
                'implementasi_metode_pengembangan' => ['required', 'string', 'max:100'],
                'implementasi_metode_pendekatan' => ['required', 'array', 'min:1'],
                'implementasi_teknologi' => ['required', 'string', 'max:255'],
                'implementasi_rencana_pengujian' => ['required', 'array', 'min:1'],
            ];
        } elseif ($request->jenis_skema === 'prestasi') {
            $rules += [
                'nama_lomba' => ['required', 'string', 'max:255'],
                'penyelenggara' => ['required', 'string', 'max:255'],
                'tingkat' => ['required', 'string', 'max:100'],
                'tanggal_pelaksanaan' => ['required', 'date'],
                'file_form_asesmen' => ['required', 'file', 'mimes:pdf', 'max:5120'],
                'file_sertifikat' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
                'file_presentasi' => ['required', 'file', 'mimes:pdf,ppt,pptx', 'max:20480'],
                'url_produk' => ['required', 'url', 'max:255'],
            ];
        }

        $validated = $request->validate($rules);

        // 3. Database Transaction & Insert
        DB::beginTransaction();
        try {
            // Update nomor kontak mahasiswa
            if ($user->student) {
                $user->student->update([
                    'phone' => $validated['phone'],
                    'guardian_phone' => $validated['guardian_phone'],
                ]);
            }

            // 3. Update pengajuan yang ada jika status revisi/ditolak, atau buat baru jika belum pernah
            if ($existing) {
                $pengajuan = $existing;
                $pengajuan->update([
                    'jenis_skema' => $validated['jenis_skema'],
                    'status_pengajuan' => 'pending',
                ]);

                // Bersihkan relasi skema sebelumnya jika skema diubah
                if ($validated['jenis_skema'] !== 'perancangan') {
                    $pengajuan->detailPerancangan()?->delete();
                }
                if ($validated['jenis_skema'] !== 'implementasi') {
                    $pengajuan->detailImplementasi()?->delete();
                }
                if ($validated['jenis_skema'] !== 'prestasi') {
                    $pengajuan->detailPrestasi()?->delete();
                }
            } else {
                $pengajuan = PengajuanPa::create([
                    'user_id' => $user->id,
                    'jenis_skema' => $validated['jenis_skema'],
                    'status_pengajuan' => 'pending',
                ]);
            }

            // Insert atau Update ke child table sesuai skema
            if ($validated['jenis_skema'] === 'perancangan') {
                DetailPerancangan::updateOrCreate(
                    ['pengajuan_pa_id' => $pengajuan->id],
                    [
                        'judul_pa' => $validated['perancangan_judul_pa'],
                        'lokasi_penelitian' => $validated['perancangan_lokasi_penelitian'],
                        'pendahuluan' => $validated['perancangan_pendahuluan'],
                        'dosen_pembimbing' => $validated['perancangan_dosen_pembimbing'],
                        'proses_bisnis' => $validated['perancangan_proses_bisnis'],
                        'jenis_sistem' => $validated['perancangan_jenis_sistem'],
                        'jumlah_aktor' => $validated['perancangan_jumlah_aktor'],
                        'fitur_utama' => $validated['perancangan_fitur_utama'],
                        'metode_pengembangan' => $validated['perancangan_metode_pengembangan'],
                        'metode_pendekatan' => $validated['perancangan_metode_pendekatan'],
                        'tools_perancangan' => $validated['perancangan_tools_perancangan'],
                    ]
                );
            } elseif ($validated['jenis_skema'] === 'implementasi') {
                DetailImplementasi::updateOrCreate(
                    ['pengajuan_pa_id' => $pengajuan->id],
                    [
                        'judul_pa' => $validated['implementasi_judul_pa'],
                        'lokasi_penelitian' => $validated['implementasi_lokasi_penelitian'],
                        'pendahuluan' => $validated['implementasi_pendahuluan'],
                        'dosen_pembimbing' => $validated['implementasi_dosen_pembimbing'],
                        'proses_bisnis' => $validated['implementasi_proses_bisnis'],
                        'jenis_sistem' => $validated['implementasi_jenis_sistem'],
                        'jumlah_aktor' => $validated['implementasi_jumlah_aktor'],
                        'fitur_utama' => $validated['implementasi_fitur_utama'],
                        'metode_pengembangan' => $validated['implementasi_metode_pengembangan'],
                        'metode_pendekatan' => $validated['implementasi_metode_pendekatan'],
                        'teknologi' => $validated['implementasi_teknologi'],
                        'rencana_pengujian' => $validated['implementasi_rencana_pengujian'],
                    ]
                );
            } elseif ($validated['jenis_skema'] === 'prestasi') {
                $folder = 'pengajuan_prestasi/' . $user->id;
                $fileAsesmen = $request->file('file_form_asesmen')->store($folder, 'public');
                $fileSertifikat = $request->file('file_sertifikat')->store($folder, 'public');
                $filePresentasi = $request->file('file_presentasi')->store($folder, 'public');

                DetailPrestasi::updateOrCreate(
                    ['pengajuan_pa_id' => $pengajuan->id],
                    [
                        'nama_lomba' => $validated['nama_lomba'],
                        'penyelenggara' => $validated['penyelenggara'],
                        'tingkat' => $validated['tingkat'],
                        'tanggal_pelaksanaan' => $validated['tanggal_pelaksanaan'],
                        'file_form_asesmen' => $fileAsesmen,
                        'file_sertifikat' => $fileSertifikat,
                        'file_presentasi' => $filePresentasi,
                        'url_produk' => $validated['url_produk'],
                    ]
                );
            }

            // Sinkronisasi ke tabel final_projects agar kompatibel dengan modul lain di PAMASESA
            $pengajuan->load(['detailPerancangan', 'detailImplementasi', 'detailPrestasi', 'user.student']);
            $pengajuan->syncToFinalProject('SUBMITTED');

            DB::commit();

            $successMsg = $existing 
                ? 'Revisi pengajuan Judul Proyek Akhir skema ' . ucfirst($validated['jenis_skema']) . ' berhasil disimpan dan sedang menunggu verifikasi ulang.'
                : 'Pengajuan Judul Proyek Akhir skema ' . ucfirst($validated['jenis_skema']) . ' berhasil disimpan dan sedang menunggu verifikasi.';

            return redirect()->route('student.pengajuan.create')->with('success', $successMsg);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan pengajuan: ' . $e->getMessage()]);
        }
    }

    public function exportPdf(int|string $id)
    {
        $user = auth()->user();

        $pengajuan = PengajuanPa::with(['user.student', 'detailPerancangan', 'detailImplementasi', 'detailPrestasi'])
            ->findOrFail($id);

        // Otorisasi: Mahasiswa hanya boleh mencetak pengajuannya sendiri
        if (strtoupper($user->role) === 'MAHASISWA' && $pengajuan->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh berkas ini.');
        }

        $student = $pengajuan->user?->student;
        $detail = $pengajuan->detail;
        $nim = $student?->nim ?? 'mahasiswa';

        $pdf = Pdf::loadView('pdf.pengajuan_judul', [
            'pengajuan' => $pengajuan,
            'student' => $student,
            'user' => $pengajuan->user,
            'detail' => $detail,
        ]);

        // Atur ukuran kertas A4 portrait
        $pdf->setPaper('a4', 'portrait');

        // Tampilkan preview PDF di browser (stream)
        return $pdf->stream('Form_Pengajuan_Judul_' . $nim . '.pdf');
    }
}
