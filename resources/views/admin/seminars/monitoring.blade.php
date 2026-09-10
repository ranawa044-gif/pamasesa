@extends('layouts.app', ['heading' => 'Monitoring Seminar'])

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Menunggu Penilaian</div>
                <div class="fs-3 fw-semibold">{{ $stats['waitingAssessment'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Seminar Selesai</div>
                <div class="fs-3 fw-semibold">{{ $stats['finished'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Lulus Seminar</div>
                <div class="fs-3 fw-semibold">{{ $stats['passed'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Seminar Ulang</div>
                <div class="fs-3 fw-semibold">{{ $stats['repeat'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold">Tabel Monitoring Seminar</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mahasiswa</th>
                    <th>Judul</th>
                    <th>Pembimbing 1</th>
                    <th>Pembimbing 2</th>
                    <th>Nilai</th>
                    <th>Keputusan</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($seminarSchedules as $schedule)
                    @php
                        $scores = $schedule->assessments->pluck('total_score');
                        $average = $scores->count() ? round($scores->avg()) : null;
                        if ($schedule->assessments->contains('decision', 'REPEAT')) {
                            $finalDecision = 'REPEAT';
                        } elseif ($schedule->assessments->contains('decision', 'PASSED_WITH_REVISION')) {
                            $finalDecision = 'PASSED_WITH_REVISION';
                        } elseif ($schedule->assessments->count() === 2) {
                            $finalDecision = 'PASSED';
                        } else {
                            $finalDecision = null;
                        }
                    @endphp
                    <tr>
                        <td>{{ $schedule->seminarProposal->finalProject->student->nama ?? '-' }}</td>
                        <td>{{ $schedule->seminarProposal->finalProject->title ?? '-' }}</td>
                        <td>{{ $schedule->seminarProposal->finalProject->supervisorOne()?->lecturer?->nama ?? '-' }}</td>
                        <td>{{ $schedule->seminarProposal->finalProject->supervisorTwo()?->lecturer?->nama ?? '-' }}</td>
                        <td>{{ $average ? $average . ' / 100' : '-' }}</td>
                        <td>
                            @if($finalDecision)
                                <x-status-badge :status="$finalDecision" />
                            @else
                                <span class="text-muted">Belum lengkap</span>
                            @endif
                        </td>
                        <td><x-status-badge :status="$schedule->status" /></td>
                        <td class="text-end">
                            @if($schedule->status === 'FINISHED')
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.seminars.report', $schedule) }}">Berita Acara</a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data seminar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
