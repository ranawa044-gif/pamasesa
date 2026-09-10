@extends('layouts.app', ['heading' => 'Seminar Proposal Saya'])

@section('content')
<div class="d-flex justify-content-end mb-3">
    @if($finalProject && !$finalProject->seminarProposal)
        <a class="btn btn-primary" href="{{ route('student.seminars.create') }}">Daftar Seminar</a>
    @endif
</div>

@if(!$finalProject)
    <div class="alert alert-info">Silakan ajukan judul Proyek Akhir terlebih dahulu.</div>
@else
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <h5 class="mb-3">Kondisi Seminar Proposal</h5>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Judul ACC</span>
                    <span class="badge bg-{{ $finalProject->status === 'APPROVED' ? 'success' : 'secondary' }}">{{ $finalProject->status === 'APPROVED' ? '✓' : '✕' }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Pembimbing 1</span>
                    <span class="badge bg-{{ $finalProject->supervisorOne() ? 'success' : 'secondary' }}">{{ $finalProject->supervisorOne() ? '✓' : '✕' }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Pembimbing 2</span>
                    <span class="badge bg-{{ $finalProject->supervisorTwo() ? 'success' : 'secondary' }}">{{ $finalProject->supervisorTwo() ? '✓' : '✕' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($finalProject->seminarProposal)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h5 class="mb-2">Status: <x-status-badge :status="$finalProject->seminarProposal->status" /></h5>
                <p>File proposal: <a href="{{ asset('storage/' . $finalProject->seminarProposal->proposal_file) }}" target="_blank">Lihat</a></p>
                @if($finalProject->seminarProposal->student_note)
                    <p>Catatan: {{ $finalProject->seminarProposal->student_note }}</p>
                @endif
                <p>Pembimbing 1: {{ $finalProject->supervisorOne()?->lecturer?->nama ?? '-' }} - <x-status-badge :status="$finalProject->seminarProposal->supervisor_one_approval" /></p>
                <p>Pembimbing 2: {{ $finalProject->supervisorTwo()?->lecturer?->nama ?? '-' }} - <x-status-badge :status="$finalProject->seminarProposal->supervisor_two_approval" /></p>
                @if($finalProject->seminarProposal->schedule)
                    <p>Jadwal: {{ $finalProject->seminarProposal->schedule->date }} {{ $finalProject->seminarProposal->schedule->start_time }}-{{ $finalProject->seminarProposal->schedule->end_time }}</p>
                    <p>Ruangan: {{ $finalProject->seminarProposal->schedule->room?->name ?? '-' }}</p>
                    @php
                        $assessments = $finalProject->seminarProposal->schedule->assessments ?? collect();
                        $studentFinalDecision = $assessments->count() === 2 ? ($assessments->contains('decision', 'REPEAT') ? 'REPEAT' : 'PASSED') : null;
                    @endphp
                    @if($studentFinalDecision)
                        <div class="mt-3 border-top pt-3">
                            <h6>Hasil Seminar Proposal</h6>
                            <p>Nilai akhir: {{ round($assessments->avg('total_score'), 0) }} / 100</p>
                            <p>Keputusan: <x-status-badge :status="$studentFinalDecision" /></p>
                        </div>
                    @endif
                    @if($finalProject->seminarProposal->schedule->revisions->isNotEmpty())
                        <div class="mt-3 border-top pt-3">
                            <h6>Catatan Revisi</h6>
                            <ul class="list-group">
                                @foreach($finalProject->seminarProposal->schedule->revisions as $revision)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">{{ $revision->revision_category }}</div>
                                            <div>{{ $revision->revision_note }}</div>
                                            <div class="text-muted small">Dari: {{ $revision->lecturer?->nama ?? '-' }}</div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $revision->status === 'DONE' ? 'success' : ($revision->status === 'WAITING_VALIDATION' ? 'info' : 'warning') }} mb-2">{{ $revision->status }}</span>
                                            @if(in_array($revision->status, ['OPEN', 'REVISION'], true))
                                                <form method="post" action="{{ route('student.seminars.revisions.complete', $revision) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label class="form-label small">Catatan Revisi</label>
                                                        <textarea name="student_note" class="form-control form-control-sm" rows="2" required>{{ old('student_note') }}</textarea>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small">Bukti Revisi</label>
                                                        <input type="file" name="revision_file" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-primary">Kirim Bukti Revisi</button>
                                                </form>
                                            @elseif($revision->status === 'WAITING_VALIDATION')
                                                <div class="small text-muted mt-2">Menunggu validasi pembimbing.</div>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-info">Belum mendaftar seminar.</div>
    @endif
@endif
@endsection
