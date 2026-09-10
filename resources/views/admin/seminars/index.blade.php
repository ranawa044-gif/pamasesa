@extends('layouts.app', ['heading' => 'Seminar Proposal'])

@section('content')
<div class="row mb-3">
    <div class="col-md-9">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card p-3">
                    <div class="small text-muted">Total Siap Dijadwalkan</div>
                    <div class="h4 fw-bold">{{ $readyCount ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div class="small text-muted">Sudah Terjadwal</div>
                    <div class="h4 fw-bold">{{ $scheduledCount ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div class="small text-muted">Belum Terjadwalkan</div>
                    <div class="h4 fw-bold">{{ $notScheduledCount ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 text-end">
        <a class="btn btn-primary" href="{{ route('admin.seminars.auto.form') }}">GENERATE JADWAL</a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Mahasiswa</th><th>Judul</th><th>Pembimbing 1</th><th>Pembimbing 2</th><th>Status Seminar</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($proposals as $proposal)
                    <tr>
                        <td>{{ $proposal->finalProject->student->nama }}</td>
                        <td>{{ $proposal->finalProject->title }}</td>
                        <td><x-status-badge :status="$proposal->supervisor_one_approval" /></td>
                        <td><x-status-badge :status="$proposal->supervisor_two_approval" /></td>
                        <td><x-status-badge :status="$proposal->status" /></td>
                        <td class="text-end">
                            @if(in_array($proposal->status, ['APPROVED_BY_SUPERVISORS', 'READY_TO_SCHEDULE']))
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.seminars.schedule.create', $proposal) }}">Jadwalkan</a>
                            @else
                                <span class="text-muted small">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada seminar proposal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
