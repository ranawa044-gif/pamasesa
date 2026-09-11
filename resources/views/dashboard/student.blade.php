@extends('layouts.app', ['heading' => 'Dashboard Mahasiswa'])

@section('content')
<!-- Section Pengumuman & Dokumen Kelengkapan PA dari Admin -->
@if(isset($announcements) && $announcements->isNotEmpty())
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <span class="fw-bold"><i class="bi bi-megaphone-fill text-primary me-2"></i>Informasi & Dokumen Kelengkapan PA</span>
            <span class="badge bg-indigo-subtle text-primary border">Pengumuman Admin</span>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                @foreach($announcements as $info)
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 border position-relative hover-shadow transition-all">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    @if($info->is_pinned)
                                        <span class="badge bg-amber-subtle text-warning border border-amber-subtle"><i class="bi bi-pin-angle-fill me-1"></i>Penting / Pinned</span>
                                    @endif
                                    <h6 class="fw-bold mb-0 text-slate-800 fs-6">{{ $info->title }}</h6>
                                </div>
                                <span class="text-muted small"><i class="bi bi-clock me-1"></i>{{ $info->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                            <p class="text-slate-700 small mb-3" style="white-space: pre-line;">{{ $info->content }}</p>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                @if($info->attachment_file)
                                    <a href="{{ route('admin.announcements.download', $info) }}" class="btn btn-sm btn-primary shadow-sm" target="_blank">
                                        <i class="bi bi-download me-1"></i> Unduh File Lampiran
                                    </a>
                                @endif
                                @if($info->attachment_url)
                                    <a href="{{ $info->attachment_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Tautan Eksternal
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@php
    $hasProject = (bool)($finalProject || $pengajuanPa);
    $activeStatus = $pengajuanPa?->status_pengajuan ?? strtolower($finalProject?->status ?? '');
    $judulUtama = $pengajuanPa?->judul_tampil ?? $finalProject?->title;
    $detailData = $pengajuanPa?->detail;
    $isApproved = ($activeStatus === 'approved');
    $isRevision = ($activeStatus === 'revision');
    $isRejected = ($activeStatus === 'rejected');
    $isPending = in_array($activeStatus, ['pending', 'review', 'submitted']);
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="stat-icon stat-icon-indigo">
                    <i class="bi bi-file-earmark-check-fill"></i>
                </div>
                <div class="stat-label">Status Judul Proyek Akhir</div>
                <div class="mt-1">
                    @if($isApproved)
                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Disetujui</span>
                    @elseif($isRevision)
                        <span class="badge bg-warning text-dark"><i class="bi bi-pencil-square me-1"></i>Perlu Revisi</span>
                    @elseif($isRejected)
                        <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                    @elseif($isPending)
                        <span class="badge bg-primary"><i class="bi bi-clock-history me-1"></i>Menunggu Verifikasi</span>
                    @elseif($finalProject)
                        <x-status-badge :status="$finalProject->status" />
                    @else
                        <span class="badge bg-secondary">Belum diajukan</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="stat-icon stat-icon-teal">
                    <i class="bi bi-person-fill-check"></i>
                </div>
                <div class="stat-label">Pembimbing 1</div>
                <div class="fw-bold text-slate-800 fs-6 text-truncate">
                    @if($finalProject?->supervisorOne()?->lecturer?->nama)
                        {{ $finalProject->supervisorOne()->lecturer->nama }}
                    @elseif($pengajuanPa?->pembimbing_tampil && $pengajuanPa->pembimbing_tampil !== '-')
                        {{ $pengajuanPa->pembimbing_tampil }} <span class="badge bg-light text-muted border ms-1 fw-normal" style="font-size: 0.65rem;">(Usulan)</span>
                    @elseif($finalProject?->proposed_supervisor_name && $finalProject->proposed_supervisor_name !== '-')
                        {{ $finalProject->proposed_supervisor_name }} <span class="badge bg-light text-muted border ms-1 fw-normal" style="font-size: 0.65rem;">(Usulan)</span>
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="stat-icon stat-icon-cyan">
                    <i class="bi bi-person-fill-gear"></i>
                </div>
                <div class="stat-label">Pembimbing 2</div>
                <div class="fw-bold text-slate-800 fs-6 text-truncate">
                    {{ $finalProject?->supervisorTwo()?->lecturer?->nama ?? '-' }}
                </div>
            </div>
        </div>
    </div>
</div>

@if($hasProject)
    <!-- Informasi Detail Judul & Progress -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span class="fw-bold"><i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Detail Proyek Akhir</span>
            <div>
                @if($pengajuanPa)
                    @if($pengajuanPa->jenis_skema === 'perancangan')
                        <span class="badge bg-primary-subtle text-primary border"><i class="bi bi-pencil-ruler me-1"></i>Skema Perancangan</span>
                    @elseif($pengajuanPa->jenis_skema === 'implementasi')
                        <span class="badge bg-indigo-subtle text-primary border"><i class="bi bi-code-slash me-1"></i>Skema Implementasi</span>
                    @elseif($pengajuanPa->jenis_skema === 'prestasi')
                        <span class="badge bg-warning-subtle text-warning-emphasis border"><i class="bi bi-trophy-fill me-1"></i>Skema Prestasi</span>
                    @endif
                @else
                    <span class="badge bg-indigo-subtle text-primary border">{{ $finalProject->system_type ?? 'Sistem Informasi' }}</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <h3 class="h5 fw-bold text-slate-900 mb-2">{{ $judulUtama }}</h3>
            
            <div class="text-muted small mb-3 d-flex flex-wrap gap-3">
                @if($pengajuanPa && $pengajuanPa->jenis_skema === 'prestasi')
                    <span><i class="bi bi-building me-1"></i>Penyelenggara: <strong class="text-dark">{{ $detailData?->penyelenggara ?? '-' }}</strong></span>
                    <span><i class="bi bi-award me-1"></i>Tingkat: <strong class="text-dark">{{ $detailData?->tingkat ?? '-' }}</strong></span>
                    <span><i class="bi bi-calendar-event me-1"></i>Pelaksanaan: <strong class="text-dark">{{ $detailData?->tanggal_pelaksanaan ?? '-' }}</strong></span>
                @else
                    <span><i class="bi bi-geo-alt me-1"></i>Lokasi/Objek: <strong class="text-dark">{{ $detailData?->lokasi_penelitian ?? $finalProject?->research_object ?? '-' }}</strong></span>
                    <span><i class="bi bi-cpu me-1"></i>Teknologi/Tools: <strong class="text-dark">{{ $detailData?->teknologi ?? $detailData?->tools_perancangan ?? $finalProject?->technology ?? '-' }}</strong></span>
                    <span><i class="bi bi-window me-1"></i>Jenis Sistem: <strong class="text-dark">{{ $detailData?->jenis_sistem ?? $finalProject?->system_type ?? 'Sistem Informasi' }}</strong></span>
                @endif
            </div>

            <!-- Status Banner Alert -->
            @if($isPending)
                <div class="alert alert-primary bg-primary-subtle border-primary-subtle p-3 rounded-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-hourglass-split text-primary fs-4 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1 text-primary">Pengajuan Judul Sedang Diproses</h6>
                            <div class="small text-slate-700">Usulan judul Anda telah terkirim dan saat ini sedang dalam antrean verifikasi oleh Koordinator/Admin PA.</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($pengajuanPa)
                            <a href="{{ route('student.pengajuan.export-pdf', $pengajuanPa->id) }}" class="btn btn-sm btn-primary shadow-sm" target="_blank">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Form PDF
                            </a>
                        @endif
                        <a href="{{ route('student.pengajuan.create') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Lihat Data Isian
                        </a>
                    </div>
                </div>
            @elseif($isRevision)
                <div class="alert alert-warning border-warning p-3 rounded-3 mb-4">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-4 mt-1"></i>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 text-warning-emphasis">Pengajuan Judul Memerlukan Revisi</h6>
                            <div class="p-2 bg-white rounded border border-warning-subtle text-slate-800 small my-2">
                                <strong>Catatan Reviewer:</strong> {{ $pengajuanPa?->catatan_review ?? $finalProject?->review_note ?? 'Silakan perbaiki data pengajuan Anda sesuai arahan.' }}
                            </div>
                            <div class="text-muted small">Silakan sesuaikan isi pengajuan judul Anda sesuai catatan di atas, kemudian kirimkan kembali.</div>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <a href="{{ route('student.pengajuan.create') }}" class="btn btn-sm btn-warning shadow-sm fw-bold">
                            <i class="bi bi-pencil-square me-1"></i> Perbaiki & Ajukan Ulang Sekarang
                        </a>
                    </div>
                </div>
            @elseif($isRejected)
                <div class="alert alert-danger border-danger p-3 rounded-3 mb-4">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-x-circle-fill text-danger fs-4 mt-1"></i>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1 text-danger">Pengajuan Judul Ditolak</h6>
                            <div class="p-2 bg-white rounded border border-danger-subtle text-slate-800 small my-2">
                                <strong>Alasan Penolakan:</strong> {{ $pengajuanPa?->catatan_review ?? $finalProject?->review_note ?? 'Judul Anda tidak disetujui oleh Koordinator PA.' }}
                            </div>
                            <div class="text-muted small">Anda dapat berdiskusi dengan calon pembimbing atau koordinator PA untuk merumuskan judul alternatif.</div>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <a href="{{ route('student.pengajuan.create') }}" class="btn btn-sm btn-danger shadow-sm">
                            <i class="bi bi-arrow-repeat me-1"></i> Buat Pengajuan Baru
                        </a>
                    </div>
                </div>
            @elseif($isApproved)
                <div class="alert alert-success bg-success-subtle border-success-subtle p-3 rounded-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-success-emphasis">Pengajuan Judul Telah Disetujui (ACC)!</h6>
                            <div class="small text-slate-700">Judul Proyek Akhir Anda telah disetujui resmi. Silakan berkonsultasi dengan pembimbing dan persiapkan seminar proposal.</div>
                        </div>
                    </div>
                    @if($pengajuanPa)
                        <a href="{{ route('student.pengajuan.export-pdf', $pengajuanPa->id) }}" class="btn btn-sm btn-success shadow-sm" target="_blank">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Form PDF
                        </a>
                    @endif
                </div>
            @endif

            @if($finalProject?->titleReview)
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-slate-800 small"><i class="bi bi-ui-checks text-primary me-1"></i>Hasil Rubrik Penilaian Judul dari Admin</span>
                        <span class="badge bg-primary fs-6">{{ $finalProject->titleReview->total_score }} / 50</span>
                    </div>
                    <div class="row g-2 text-center small">
                        <div class="col"><div class="p-2 bg-white rounded border"><div class="text-muted" style="font-size:0.75rem;">Permasalahan</div><strong class="text-dark">{{ $finalProject->titleReview->problem_score }}/10</strong></div></div>
                        <div class="col"><div class="p-2 bg-white rounded border"><div class="text-muted" style="font-size:0.75rem;">Solusi</div><strong class="text-dark">{{ $finalProject->titleReview->solution_score }}/10</strong></div></div>
                        <div class="col"><div class="p-2 bg-white rounded border"><div class="text-muted" style="font-size:0.75rem;">Kompleksitas</div><strong class="text-dark">{{ $finalProject->titleReview->complexity_score }}/10</strong></div></div>
                        <div class="col"><div class="p-2 bg-white rounded border"><div class="text-muted" style="font-size:0.75rem;">Metode</div><strong class="text-dark">{{ $finalProject->titleReview->method_score }}/10</strong></div></div>
                        <div class="col"><div class="p-2 bg-white rounded border"><div class="text-muted" style="font-size:0.75rem;">Pengujian</div><strong class="text-dark">{{ $finalProject->titleReview->testing_score }}/10</strong></div></div>
                    </div>
                </div>
            @endif

            @if($isApproved)
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold text-slate-700 small">Kemajuan Overall (Implementasi & Bimbingan)</span>
                        <span class="fw-bold text-primary small">{{ $progressAverage }}%</span>
                    </div>
                    <div class="progress" style="height: 0.75rem;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $progressAverage }}%" aria-valuenow="{{ $progressAverage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">Status Progress Terakhir</div>
                            <div class="fw-bold text-slate-800 mt-1 mb-1">{{ $lastProgress?->progress_type ?? '-' }}</div>
                            <x-status-badge :status="$lastProgress?->status ?? 'WAITING'" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">Kelayakan Sidang Akhir</div>
                            <div class="fw-bold text-slate-800 mt-1">
                                @if($finalProject?->readyForDefense())
                                    <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Siap Mendaftar Sidang</span>
                                @else
                                    <span class="text-muted"><i class="bi bi-hourglass-top me-1"></i>Belum Memenuhi Syarat</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($finalProject?->seminarProposal?->schedule)
                    <div class="p-3 bg-indigo-subtle border border-indigo-subtle rounded-3 mb-3">
                        <div class="fw-bold text-primary mb-2"><i class="bi bi-calendar-event me-2"></i>Jadwal Seminar Proposal Anda</div>
                        <div class="row g-2 text-slate-800 small">
                            <div class="col-md-4"><strong>Tanggal:</strong> {{ $finalProject->seminarProposal->schedule->date }}</div>
                            <div class="col-md-4"><strong>Waktu:</strong> <span class="font-mono">{{ $finalProject->seminarProposal->schedule->start_time }} - {{ $finalProject->seminarProposal->schedule->end_time }}</span></div>
                            <div class="col-md-4"><strong>Ruangan:</strong> {{ $finalProject->seminarProposal->schedule->room?->name ?? '-' }}</div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <div>Jadwal Seminar Proposal belum diterbitkan oleh Admin.</div>
                    </div>
                @endif
            @else
                <div class="p-3 bg-light rounded-3 border">
                    <div class="row g-3">
                        @if($detailData?->pendahuluan)
                            <div class="col-12">
                                <span class="text-muted small fw-semibold d-block">Ringkasan Pendahuluan:</span>
                                <p class="small text-slate-800 mb-0" style="white-space: pre-line;">{{ Str::limit($detailData->pendahuluan, 300) }}</p>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <span class="text-muted small fw-semibold d-block">Dosen Pembimbing Usulan:</span>
                            <span class="fw-bold text-slate-800 small">{{ $pengajuanPa?->pembimbing_tampil ?? '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small fw-semibold d-block">Tanggal Pengajuan:</span>
                            <span class="text-slate-800 small">{{ $pengajuanPa?->created_at?->translatedFormat('d F Y, H:i') ?? '-' }} WIB</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Status Jalur Kerja -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom fw-bold">
            <i class="bi bi-signpost-split-fill text-primary me-2"></i>Tahapan & Status Jalur Kerja PA
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush rounded-bottom">
                <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-slate-800">1. Pengajuan & Validasi Judul</div>
                        <div class="text-muted small">Status persetujuan usulan judul oleh admin</div>
                    </div>
                    @if($isApproved)
                        <span class="badge bg-emerald-subtle text-success border"><i class="bi bi-check-circle-fill me-1"></i>Disetujui</span>
                    @elseif($isRevision)
                        <span class="badge bg-warning-subtle text-warning border"><i class="bi bi-pencil-square me-1"></i>Perlu Revisi</span>
                    @elseif($isRejected)
                        <span class="badge bg-danger-subtle text-danger border"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                    @else
                        <span class="badge bg-primary-subtle text-primary border"><i class="bi bi-clock-history me-1"></i>Menunggu Verifikasi</span>
                    @endif
                </div>
                <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-slate-800">2. Seminar Proposal</div>
                        <div class="text-muted small">Pelaksanaan dan hasil ujian seminar proposal</div>
                    </div>
                    <div>
                        @if($finalProject?->seminarProposal)
                            <x-status-badge :status="$finalProject->seminarProposal->status" class="text-dark" />
                        @elseif($isApproved)
                            <span class="badge bg-light text-muted border">Belum Terdaftar</span>
                        @else
                            <span class="badge bg-light text-muted border">Menunggu Judul ACC</span>
                        @endif
                    </div>
                </div>
                <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-slate-800">3. Implementasi & Progress Bimbingan</div>
                        <div class="text-muted small">Persentase pengerjaan bimbingan berkala</div>
                    </div>
                    @if($isApproved)
                        <span class="badge bg-indigo-subtle text-primary border font-mono fs-6">{{ $progressAverage }}%</span>
                    @else
                        <span class="badge bg-light text-muted border font-mono">0%</span>
                    @endif
                </div>
                <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-slate-800">4. Sidang Akhir</div>
                        <div class="text-muted small">Tahap persetujuan pendaftaran sidang akhir</div>
                    </div>
                    <div>
                        @if($finalProject?->readyForDefense())
                            <span class="badge bg-emerald-subtle text-success border"><i class="bi bi-check-lg me-1"></i>Siap Daftar</span>
                        @else
                            <span class="badge bg-light text-muted border">Menunggu Syarat</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info d-flex align-items-center gap-3 p-4 rounded-3 shadow-sm border-0">
        <i class="bi bi-info-circle-fill text-primary fs-2"></i>
        <div>
            <h5 class="fw-bold mb-1 text-slate-900">Belum Ada Pengajuan Judul</h5>
            <div class="text-slate-600 mb-2">Anda belum mengajukan judul Proyek Akhir. Silakan klik tombol di bawah untuk mengisi formulir pengajuan judul.</div>
            <a href="{{ route('student.pengajuan.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Mulai Pengajuan Judul Sekarang
            </a>
        </div>
    </div>
@endif
@endsection

