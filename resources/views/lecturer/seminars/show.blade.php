@extends('layouts.app', ['heading' => 'Detail Pengajuan Seminar'])

@section('content')
<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5>{{ $seminarProposal->finalProject->student->nama }} - {{ $seminarProposal->finalProject->title }}</h5>
                <p>Status: <x-status-badge :status="$seminarProposal->status" /></p>
                <p>File proposal: <a href="{{ asset('storage/' . $seminarProposal->proposal_file) }}" target="_blank">Lihat</a></p>
                @if($seminarProposal->student_note)
                    <p>Catatan mahasiswa: {{ $seminarProposal->student_note }}</p>
                @endif
                <p>Progress terakhir: {{ $seminarProposal->finalProject->progressLogs->first()?->progress_type ?? '-' }} - {{ $seminarProposal->finalProject->progressLogs->first()?->status ?? '-' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Timeline Seminar</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Pengajuan <span class="badge bg-{{ in_array($seminarProposal->status, ['WAITING_APPROVAL','REVISION','APPROVED_BY_SUPERVISORS','READY_TO_SCHEDULE','SCHEDULED','FINISHED']) ? 'success' : 'secondary' }} ms-2">✓</span></li>
                    <li class="list-group-item">Approval Pembimbing 1 <span class="badge bg-{{ $seminarProposal->supervisor_one_approval === 'APPROVED' ? 'success' : ($seminarProposal->supervisor_one_approval === 'REVISION' ? 'warning' : 'secondary') }} ms-2">{{ $seminarProposal->supervisor_one_approval }}</span></li>
                    <li class="list-group-item">Approval Pembimbing 2 <span class="badge bg-{{ $seminarProposal->supervisor_two_approval === 'APPROVED' ? 'success' : ($seminarProposal->supervisor_two_approval === 'REVISION' ? 'warning' : 'secondary') }} ms-2">{{ $seminarProposal->supervisor_two_approval }}</span></li>
                    <li class="list-group-item">Siap Dijadwalkan <span class="badge bg-{{ $seminarProposal->status === 'APPROVED_BY_SUPERVISORS' || $seminarProposal->status === 'READY_TO_SCHEDULE' ? 'success' : 'secondary' }} ms-2">{{ $seminarProposal->status === 'APPROVED_BY_SUPERVISORS' || $seminarProposal->status === 'READY_TO_SCHEDULE' ? '✓' : '✕' }}</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="post" action="{{ route('lecturer.seminars.review', $seminarProposal) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Keputusan</label>
                <select name="approval" class="form-select" required>
                    <option value="APPROVED" @selected(old('approval') === 'APPROVED')>APPROVED</option>
                    <option value="REVISION" @selected(old('approval') === 'REVISION')>REVISION</option>
                </select>
                @error('approval')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                @error('note')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button class="btn btn-primary">Simpan Review</button>
        </form>
    </div>
</div>
@endsection
