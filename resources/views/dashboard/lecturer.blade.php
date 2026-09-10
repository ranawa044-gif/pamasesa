@extends('layouts.app', ['heading' => 'Dashboard Dosen'])

@section('content')
<!-- Ringkasan Statistik Dosen -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="stat-icon stat-icon-indigo">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-label">Mahasiswa Bimbingan</div>
                <div class="stat-value">{{ $projects->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="stat-icon stat-icon-teal">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="stat-label">Siap Sidang Akhir</div>
                <div class="stat-value">{{ $readyForDefenseCount ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="stat-icon stat-icon-cyan">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div class="stat-label">Tugas Sebagai Penguji</div>
                <div class="stat-value">{{ $examinerAssignmentCount ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="stat-icon stat-icon-amber">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div class="stat-label">Validasi Revisi Pending</div>
                <div class="stat-value">{{ $revisionValidationCount ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <div class="stat-icon stat-icon-rose">
                    <i class="bi bi-calendar-week-fill"></i>
                </div>
                <div class="stat-label">Seminar Minggu Ini</div>
                <div class="stat-value">{{ $thisWeekCount ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Agenda Seminar Proposal Saya -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <span class="fw-bold"><i class="bi bi-calendar-event-fill text-primary me-2"></i>Agenda Seminar Proposal Saya</span>
        <span class="badge bg-indigo-subtle text-primary border">Jadwal Menguji & Membimbing</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Mahasiswa</th>
                    <th>Judul PA</th>
                    <th>Ruangan</th>
                    <th class="text-end">Aksi / Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($seminarSchedules as $s)
                    <tr>
                        <td><span class="badge bg-light text-dark border">{{ $s->date }}</span></td>
                        <td class="font-mono text-nowrap">{{ $s->start_time }} - {{ $s->end_time }}</td>
                        <td class="fw-semibold text-slate-800">{{ $s->seminarProposal->finalProject->student->nama ?? '-' }}</td>
                        <td class="text-truncate" style="max-width: 240px;">{{ $s->seminarProposal->finalProject->title ?? '-' }}</td>
                        <td><span class="badge bg-indigo-subtle text-primary border">{{ $s->room?->name ?? '-' }}</span></td>
                        <td class="text-end">
                            @if($s->status === 'PUBLISHED' && $s->date === now()->toDateString())
                                <a class="btn btn-sm btn-success shadow-sm" href="{{ route('lecturer.seminars.start', $s) }}">
                                    <i class="bi bi-play-circle-fill me-1"></i> Mulai Ujian
                                </a>
                            @else
                                <span class="badge bg-light text-muted border">{{ $s->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-calendar-x text-muted me-1"></i> Belum ada agenda seminar terdekat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Progress Log Bimbingan Terbaru -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <span class="fw-bold"><i class="bi bi-activity text-primary me-2"></i>Progress Log Bimbingan Masuk</span>
        <span class="badge bg-light text-dark border">Perlu Penelaahan</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Judul PA</th>
                    <th>Capaian</th>
                    <th>Status</th>
                    <th>Waktu Submit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestProgress as $progress)
                    <tr>
                        <td class="fw-semibold text-slate-800">{{ $progress->finalProject->student->nama }}</td>
                        <td class="text-truncate" style="max-width: 220px;">{{ $progress->finalProject->title }}</td>
                        <td><span class="badge bg-indigo-subtle text-primary border font-mono">{{ $progress->percentage }}%</span></td>
                        <td><x-status-badge :status="$progress->status" /></td>
                        <td class="text-muted small">{{ $progress->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada progress bimbingan terbaru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Daftar Mahasiswa Bimbingan -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <span class="fw-bold"><i class="bi bi-people-fill text-primary me-2"></i>Daftar Mahasiswa Bimbingan</span>
        <a href="{{ route('lecturer.guidances.index') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye-fill me-1"></i> Kelola Bimbingan</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Judul Proyek Akhir</th>
                    <th>Progress Terakhir</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td class="fw-semibold text-slate-800">{{ $project->student->nama }}</td>
                        <td class="text-truncate" style="max-width: 280px;">{{ $project->title }}</td>
                        <td><span class="badge bg-indigo-subtle text-primary border font-mono">{{ $project->progressLogs->first()?->percentage ?? 0 }}%</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-primary" href="{{ route('lecturer.guidances.show', $project) }}">
                                <i class="bi bi-journal-check me-1"></i> Review Progress
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada mahasiswa bimbingan yang di-assign.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

