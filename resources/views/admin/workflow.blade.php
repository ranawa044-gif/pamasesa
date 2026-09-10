@extends('layouts.app', ['title' => 'Alur Sistem — PAMASESA', 'heading' => 'Alur Penjelasan Sistem'])

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-diagram-3-fill me-2"></i>Alur Proses Tahapan Proyek Akhir (PA)</h5>
        <span class="badge bg-primary-subtle text-primary border">Standard Operating Procedure</span>
    </div>
    <div class="card-body p-4">
        <p class="text-muted mb-4">Berikut adalah 8 tahap utama alur pengerjaan Proyek Akhir dari pengajuan awal hingga pelaksanaan Sidang Akhir di D3 Sistem Informasi:</p>

        <div class="timeline-hallmark ms-2">
            <!-- 1. Pengajuan Judul -->
            <div class="timeline-step">
                <div class="timeline-number">1</div>
                <h6 class="fw-bold mb-1 text-slate-800 fs-6">Pengajuan Judul (Mahasiswa)</h6>
                <p class="text-muted mb-0">Mahasiswa mengajukan judul Proyek Akhir melalui menu <span class="badge bg-light text-dark border">Pengajuan Judul</span>. Mahasiswa mengisi usulan judul beserta masalah nyata, objek/lokasi, rencana sistem, metode, tech stack, dan usulan pembimbing.</p>
            </div>

            <!-- 2. Validasi Judul & Plotting Pembimbing -->
            <div class="timeline-step">
                <div class="timeline-number">2</div>
                <h6 class="fw-bold mb-1 text-slate-800 fs-6">Validasi Judul & Plotting Pembimbing (Admin)</h6>
                <p class="text-muted mb-0">Admin mereview usulan judul mahasiswa di menu <span class="badge bg-light text-dark border">Validasi Judul</span> (ACC / Revisi / Tolak). Jika diterima, Admin melakukan plotting Dosen Pembimbing 1 dan Pembimbing 2 di menu <span class="badge bg-light text-dark border">Pembimbing</span>.</p>
            </div>

            <!-- 3. Bimbingan & Progress -->
            <div class="timeline-step">
                <div class="timeline-number">3</div>
                <h6 class="fw-bold mb-1 text-slate-800 fs-6">Bimbingan & Logging Progress (Mahasiswa & Dosen)</h6>
                <p class="text-muted mb-0">Mahasiswa mencatat aktivitas bimbingan secara berkala di menu <span class="badge bg-light text-dark border">Progress PA</span>. Dosen pembimbing menelaah, memberikan komentar, dan menyetujui progress di menu <span class="badge bg-light text-dark border">Mahasiswa Bimbingan</span>.</p>
            </div>

            <!-- 4. Pendaftaran Seminar Proposal -->
            <div class="timeline-step">
                <div class="timeline-number">4</div>
                <h6 class="fw-bold mb-1 text-slate-800 fs-6">Pendaftaran Seminar Proposal (Mahasiswa)</h6>
                <p class="text-muted mb-0">Setelah disetujui dosen pembimbing (status 'Siap Seminar'), mahasiswa mendaftar Seminar Proposal beserta mengunggah draft proposal PDF dan berkas pendukung.</p>
            </div>

            <!-- 5. Penjadwalan Seminar -->
            <div class="timeline-step">
                <div class="timeline-number">5</div>
                <h6 class="fw-bold mb-1 text-slate-800 fs-6">Penjadwalan Seminar Proposal (Admin)</h6>
                <p class="text-muted mb-0">Admin menetapkan tanggal, jam, ruangan seminar, serta Dosen Penguji secara manual atau menggunakan penjadwalan otomatis (*Auto-Schedule*).</p>
            </div>

            <!-- 6. Pelaksanaan Seminar & Revisi -->
            <div class="timeline-step">
                <div class="timeline-number">6</div>
                <h6 class="fw-bold mb-1 text-slate-800 fs-6">Pelaksanaan Seminar & Revisi (Dosen & Mahasiswa)</h6>
                <p class="text-muted mb-0">Seminar proposal dilaksanakan. Dosen penguji dan pembimbing memberikan penilaian rubrik serta daftar revisi. Mahasiswa memperbaiki dokumen dan divalidasi kembali oleh dosen penguji.</p>
            </div>

            <!-- 7. Pendaftaran Sidang Akhir -->
            <div class="timeline-step">
                <div class="timeline-number">7</div>
                <h6 class="fw-bold mb-1 text-slate-800 fs-6">Pendaftaran Sidang Akhir (Mahasiswa)</h6>
                <p class="text-muted mb-0">Setelah implementasi aplikasi selesai dan mendapat persetujuan pembimbing (status progress PA 'Lanjut Sidang'), mahasiswa mengunggah laporan akhir & tautan aplikasi untuk mendaftar Sidang Akhir.</p>
            </div>

            <!-- 8. Penjadwalan & Pelaksanaan Sidang Akhir -->
            <div class="timeline-step">
                <div class="timeline-number">8</div>
                <h6 class="fw-bold mb-1 text-slate-800 fs-6">Penjadwalan & Pelaksanaan Sidang Akhir (Admin & Dosen)</h6>
                <p class="text-muted mb-0">Admin melakukan plotting Penguji 1 & 2 serta penjadwalan Sidang Akhir. Setelah sidang terlaksana dan revisi dinyatakan valid, mahasiswa dinyatakan Lulus Proyek Akhir.</p>
            </div>
        </div>
    </div>
</div>
@endsection

