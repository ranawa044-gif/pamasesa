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
    <div class="card-header bg-white fw-semibold">Progress Mahasiswa</div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>Jenis</th><th>Persentase</th><th>Keterangan</th><th>Status</th><th>File</th><th>Revisi</th><th>Review</th></tr>
            </thead>
            <tbody>
                @forelse($finalProject->progressLogs as $progress)
                    <tr>
                        <td>{{ $progress->progress_type }}</td>
                        <td>{{ $progress->percentage }}%</td>
                        <td>{{ $progress->description }}</td>
                        <td><x-status-badge :status="$progress->status" /></td>
                        <td>
                            @if($progress->file_path)
                                <a href="{{ asset('storage/' . $progress->file_path) }}" target="_blank">Lihat File</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($progress->revision_file_path)
                                <a href="{{ asset('storage/' . $progress->revision_file_path) }}" target="_blank">Lihat Revisi</a>
                            @else
                                -
                            @endif
                        </td>
                        <td style="min-width: 280px;">
                            <form method="post" action="{{ route('lecturer.progress.review', $progress) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <select name="status" class="form-select form-select-sm mb-2">
                                    <option value="APPROVED" @selected($progress->status === 'APPROVED')>APPROVED</option>
                                    <option value="REVISION" @selected($progress->status === 'REVISION')>REVISION</option>
                                </select>
                                <textarea name="review_note" class="form-control form-control-sm mb-2" rows="2" placeholder="Komentar">{{ $progress->review_note }}</textarea>
                                <input type="file" name="review_file" class="form-control form-control-sm mb-2" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                                <button class="btn btn-sm btn-primary">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada progress.</td></tr>
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
