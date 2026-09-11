@extends('layouts.app', ['heading' => 'Detail PA Mahasiswa'])

@section('content')
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <h3 class="h5">{{ $finalProject->title }}</h3>
        <p class="text-muted">{{ $finalProject->student->nama }} - {{ $finalProject->student->nim }}</p>
        <p><strong>Objek / Lokasi:</strong> {{ $finalProject->research_object }}</p>
        <p><strong>Jenis Sistem:</strong> {{ $finalProject->system_type }}</p>
        <p><strong>Fitur Utama:</strong> {{ $finalProject->main_features }}</p>
        <p><strong>Jumlah Aktor:</strong> {{ $finalProject->actor_count }}</p>
        <p><strong>Teknologi:</strong> {{ $finalProject->technology }}</p>
        <p><strong>Metode:</strong> {{ $finalProject->development_method }} @if($finalProject->additional_method) / {{ $finalProject->additional_method }} @endif</p>
        <p><strong>Rencana Pengujian:</strong> {{ $finalProject->testing_plan }}</p>
        <div class="d-flex gap-2 align-items-center">
            <x-status-badge :status="$finalProject->status" />
            <span class="text-muted">Siap sidang: {{ $finalProject->readyForDefense() ? 'Ya' : 'Belum' }}</span>
        </div>
        <div class="mt-3">
            <div class="small text-muted">Approval Pembimbing 1</div>
            <div><x-status-badge :status="$finalProject->supervisor_one_approval" /></div>
        </div>
        <div class="mt-2">
            <div class="small text-muted">Approval Pembimbing 2</div>
            <div><x-status-badge :status="$finalProject->supervisor_two_approval" /></div>
        </div>
    </div>
</div>

<!-- ====================================================================
     FITUR ACC SEMINAR PROPOSAL (TOMBOL BESAR UNTUK DOSEN PEMBIMBING)
     ==================================================================== -->
@php
    $pengajuanPaData = $pengajuanPa ?? $finalProject->student?->user?->pengajuanPa;
    $lecturerId = auth()->user()->lecturer?->id;
    $isP1 = ($finalProject->supervisorOne()?->lecturer_id === $lecturerId);
    $isP2 = ($finalProject->supervisorTwo()?->lecturer_id === $lecturerId);
    $isSupervisor = $isP1 || $isP2;
    $myRoleName = $isP1 ? 'Pembimbing 1' : ($isP2 ? 'Pembimbing 2' : 'Dosen Pembimbing');
    $myAcc = $isP1 ? ($pengajuanPaData?->is_acc_p1 ?? false) : ($pengajuanPaData?->is_acc_p2 ?? false);
    $bothAcc = ($pengajuanPaData?->is_acc_p1 && $pengajuanPaData?->is_acc_p2);
@endphp

@if($isSupervisor)
    <div class="card shadow-sm border-0 mb-4 border-start border-4 {{ $bothAcc ? 'border-success bg-success bg-opacity-10' : ($myAcc ? 'border-primary bg-primary bg-opacity-10' : 'border-success bg-white') }}">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge {{ $bothAcc ? 'bg-success' : 'bg-primary' }} text-white px-2.5 py-1 fw-bold">
                            <i class="bi bi-mortarboard-fill me-1"></i> PERSETUJUAN SEMINAR PROPOSAL
                        </span>
                        @if($bothAcc)
                            <span class="badge bg-success text-white"><i class="bi bi-check2-all me-1"></i>KEDUA PEMBIMBING TELAH ACC</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>MENUNGGU ACC KEDUA PEMBIMBING</span>
                        @endif
                    </div>
                    <h4 class="h5 fw-bold text-slate-900 mb-1">Persetujuan Kelayakan Seminar Proposal</h4>
                    <p class="text-muted small mb-3" style="max-width: 680px;">
                        Sesuai aturan akademik, mahasiswa hanya berhak mendaftar Seminar Proposal jika <strong>KEDUA dosen pembimbing</strong> telah memberikan ACC.
                    </p>

                    <!-- Status ACC Tiap Pembimbing -->
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <div class="p-2 px-3 rounded-2 border bg-white small d-flex align-items-center gap-2">
                            <span class="text-muted">Pembimbing 1 ({{ $finalProject->supervisorOne()?->lecturer?->nama ?? 'Belum ditentukan' }}):</span>
                            @if($pengajuanPaData?->is_acc_p1)
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Sudah ACC</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Belum ACC</span>
                            @endif
                        </div>
                        <div class="p-2 px-3 rounded-2 border bg-white small d-flex align-items-center gap-2">
                            <span class="text-muted">Pembimbing 2 ({{ $finalProject->supervisorTwo()?->lecturer?->nama ?? 'Belum ditentukan' }}):</span>
                            @if($pengajuanPaData?->is_acc_p2)
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Sudah ACC</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Belum ACC</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi untuk Dosen yang Login -->
                <div class="text-lg-end flex-shrink-0">
                    @if($myAcc)
                        <div class="text-start text-lg-end mb-2">
                            <span class="badge bg-success text-white px-3 py-2 fs-6 shadow-sm">
                                <i class="bi bi-check-circle-fill me-1"></i> Anda ({{ $myRoleName }}) Sudah Memberikan ACC
                            </span>
                        </div>
                        <form method="post" action="{{ route('lecturer.guidances.acc-seminar', $finalProject) }}" onsubmit="return confirm('Batalkan persetujuan ACC Seminar Proposal dari Anda ({{ $myRoleName }})?')">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="cancel">
                            <button type="submit" class="btn btn-outline-danger btn-sm px-3 shadow-sm">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Batalkan ACC Saya
                            </button>
                        </form>
                    @else
                        <form method="post" action="{{ route('lecturer.guidances.acc-seminar', $finalProject) }}" onsubmit="return confirm('Apakah Anda yakin ingin memberikan ACC Seminar Proposal sebagai {{ $myRoleName }}?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-lg px-4 py-3 shadow fw-bold d-inline-flex align-items-center gap-2">
                                <i class="bi bi-award-fill fs-3"></i>
                                <span>ACC Seminar Proposal ({{ $myRoleName }})</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold">Status Approval Sidang</div>
    <div class="card-body">
        @if(auth()->user()->lecturer && ($finalProject->supervisorOne()?->lecturer_id === auth()->user()->lecturer->id || $finalProject->supervisorTwo()?->lecturer_id === auth()->user()->lecturer->id))
            <form method="post" action="{{ route('lecturer.guidances.approve', $finalProject) }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <label class="form-label">Status Approval</label>
                    <select name="approval_status" class="form-select" required>
                        <option value="APPROVED">APPROVED</option>
                        <option value="REVISION">REVISION</option>
                    </select>
                </div>
                <div class="col-md-8 align-self-end text-end">
                    <button class="btn btn-primary">Simpan Approval</button>
                </div>
            </form>
        @else
            <div class="text-muted">Anda bukan pembimbing untuk mahasiswa ini atau tidak memiliki akses approval.</div>
        @endif
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>Progress Bimbingan Mahasiswa</span>
        <span class="text-muted small">Status Log: <span class="badge bg-warning text-dark me-1">Menunggu Verifikasi</span> <span class="badge bg-success-subtle text-success">Terverifikasi</span></span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>Jenis</th><th>Persentase</th><th>Keterangan</th><th>Status</th><th>File Mahasiswa</th><th>File Dosen</th><th>Balasan & Verifikasi</th></tr>
            </thead>
            <tbody>
                @forelse($finalProject->progressLogs as $progress)
                    <tr>
                        <td><strong>{{ $progress->progress_type }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 14px; min-width: 60px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $progress->percentage }}%"></div>
                                </div>
                                <span class="small text-muted">{{ $progress->percentage }}%</span>
                            </div>
                        </td>
                        <td style="max-width: 240px;">
                            <div class="small text-slate-800 text-truncate" title="{{ $progress->description }}">{{ $progress->description }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $progress->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td><x-status-badge :status="$progress->status" /></td>
                        <td>
                            @if($progress->file_path)
                                <a href="{{ asset('storage/' . $progress->file_path) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" target="_blank">
                                    <i class="bi bi-file-earmark-arrow-down me-1"></i> File
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @if($progress->revision_file_path)
                                <a href="{{ asset('storage/' . $progress->revision_file_path) }}" class="btn btn-sm btn-outline-primary py-1 px-2" target="_blank">
                                    <i class="bi bi-paperclip me-1"></i> Catatan
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td style="min-width: 280px;">
                            <form method="post" action="{{ route('lecturer.progress.review', $progress) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="mb-2">
                                    <textarea name="review_note" class="form-control form-control-sm" rows="2" placeholder="Ketik balasan komentar / arahan dosen...">{{ $progress->review_note }}</textarea>
                                </div>
                                <div class="mb-2">
                                    <input type="file" name="review_file" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" title="Upload file coretan/catatan bimbingan (opsional)">
                                </div>
                                @if($progress->status === 'verified')
                                    <button type="submit" name="status" value="verified" class="btn btn-sm btn-outline-success w-100 shadow-sm">
                                        <i class="bi bi-check-circle-fill me-1"></i> Update Balasan (Terverifikasi)
                                    </button>
                                @else
                                    <button type="submit" name="status" value="verified" class="btn btn-sm btn-success w-100 shadow-sm fw-bold">
                                        <i class="bi bi-check2-circle me-1"></i> Verifikasi Progress
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada progress bimbingan dari mahasiswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white fw-semibold">Timeline Bimbingan dan Diskusi</div>
    <div class="card-body">
        @forelse($finalProject->progressLogs as $progress)
            <div class="mb-4 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>{{ $progress->progress_type }}</strong>
                        <span class="text-muted">• {{ $progress->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <x-status-badge :status="$progress->status" />
                </div>
                <p>{{ $progress->description }}</p>
                @if($progress->file_path)
                    <p class="mb-2"><a href="{{ asset('storage/' . $progress->file_path) }}" target="_blank">File mahasiswa</a></p>
                @endif
                @if($progress->revision_file_path)
                    <p class="mb-2"><a href="{{ asset('storage/' . $progress->revision_file_path) }}" target="_blank">File revisi dosen</a></p>
                @endif
                <div class="border rounded-3 p-3 bg-light">
                    <div class="mb-3"><strong class="small text-uppercase">Diskusi</strong></div>
                    @forelse($progress->comments as $comment)
                        <div class="mb-2 p-3 rounded-3 bg-white border">
                            <div class="small text-muted mb-1">{{ $comment->user->name }} • {{ $comment->created_at->format('d M Y H:i') }}</div>
                            <div>{{ $comment->comment }}</div>
                        </div>
                    @empty
                        <div class="text-muted mb-3">Belum ada komentar.</div>
                    @endforelse
                    <form method="post" action="{{ route('guidance-comments.store') }}">
                        @csrf
                        <input type="hidden" name="progress_log_id" value="{{ $progress->id }}">
                        <div class="mb-2">
                            <textarea name="comment" class="form-control" rows="2" placeholder="Tambahkan komentar" required></textarea>
                        </div>
                        <button class="btn btn-sm btn-primary">Kirim Komentar</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-muted">Belum ada riwayat progress.</div>
        @endforelse
    </div>
</div>
@endsection
