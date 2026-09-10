@extends('layouts.app', ['heading' => 'Progress PA'])

@section('content')
<div class="d-flex justify-content-end mb-3">
    @if(in_array($finalProject?->status, ['APPROVED', 'READY_FOR_DEFENSE'], true) || $finalProject?->seminarProposal?->status === 'SEMINAR_PASSED')
        <a class="btn btn-primary" href="{{ route('student.progress.create') }}">Tambah Progress</a>
    @endif
</div>
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Jenis</th><th>Persentase</th><th>File</th><th>Status</th><th>Catatan Dosen</th><th>Revisi</th></tr>
            </thead>
            <tbody>
                @forelse($finalProject?->progressLogs ?? [] as $progress)
                    <tr>
                        <td>{{ $progress->progress_type }}</td>
                        <td>
                            <div class="progress" style="height: 18px;">
                                <div class="progress-bar" style="width: {{ $progress->percentage }}%">{{ $progress->percentage }}%</div>
                            </div>
                        </td>
                        <td>
                            @if($progress->file_path)
                                <a href="{{ asset('storage/' . $progress->file_path) }}" target="_blank">Lihat File</a>
                            @else
                                -
                            @endif
                        </td>
                        <td><x-status-badge :status="$progress->status" /></td>
                        <td>{{ $progress->review_note ?? '-' }}</td>
                        <td>
                            @if($progress->revision_file_path)
                                <a href="{{ asset('storage/' . $progress->revision_file_path) }}" target="_blank">Lihat Revisi</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada progress.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($finalProject?->progressLogs->isNotEmpty())
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white fw-semibold">Timeline Riwayat Bimbingan</div>
        <div class="card-body">
            @foreach($finalProject->progressLogs as $progress)
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $progress->progress_type }}</strong>
                            <span class="text-muted">({{ $progress->created_at->format('d M Y H:i') }})</span>
                        </div>
                        <x-status-badge :status="$progress->status" />
                    </div>
                    <p class="mb-1">{{ $progress->description }}</p>
                    @if($progress->file_path)
                        <p class="mb-1"><a href="{{ asset('storage/' . $progress->file_path) }}" target="_blank">File lampiran mahasiswa</a></p>
                    @endif
                    @if($progress->revision_file_path)
                        <p class="mb-1"><a href="{{ asset('storage/' . $progress->revision_file_path) }}" target="_blank">File revisi dosen</a></p>
                    @endif
                    <div class="border rounded-3 p-3 bg-light">
                        <div class="mb-3">
                            <strong class="small text-uppercase">Diskusi</strong>
                        </div>
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
                                <textarea name="comment" class="form-control" rows="2" placeholder="Tulis komentar" required></textarea>
                            </div>
                            <button class="btn btn-sm btn-primary">Kirim Komentar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
