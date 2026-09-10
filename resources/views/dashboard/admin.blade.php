@extends('layouts.app', ['heading' => 'Dashboard Admin'])

@section('content')
<!-- Section 1: Ringkasan Umum Mahasiswa & Bimbingan -->
<div class="mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h5 class="fw-bold mb-0 text-slate-800"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Ringkasan Umum & Progress</h5>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.backup.index') }}" class="btn btn-sm btn-outline-primary shadow-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-database-fill-down"></i> Panel Backup
            </a>
            <a href="{{ route('admin.backup.download') }}" class="btn btn-sm btn-primary shadow-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-cloud-arrow-down-fill"></i> Download .SQL
            </a>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-4 col-lg">
            <div class="card card-stat shadow-sm">
                <div class="card-body">
                    <div class="stat-icon stat-icon-indigo">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-label">Mahasiswa Aktif</div>
                    <div class="stat-value">{{ $studentCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg">
            <div class="card card-stat shadow-sm">
                <div class="card-body">
                    <div class="stat-icon stat-icon-teal">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <div class="stat-label">Progress Rata-rata</div>
                    <div class="stat-value">{{ $progressAverage }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg">
            <div class="card card-stat shadow-sm">
                <div class="card-body">
                    <div class="stat-icon stat-icon-cyan">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="stat-label">Skor Review Judul</div>
                    <div class="stat-value">{{ $titleReviewAverage }}<span class="fs-6 text-muted font-normal">/50</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg">
            <div class="card card-stat shadow-sm">
                <div class="card-body">
                    <div class="stat-icon stat-icon-amber">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stat-label">Review Judul Pending</div>
                    <div class="stat-value">{{ $pendingTitleReviewCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg">
            <div class="card card-stat shadow-sm">
                <div class="card-body">
                    <div class="stat-icon stat-icon-rose">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <div class="stat-label">Belum Ada Pembimbing</div>
                    <div class="stat-value">{{ $studentsWithoutSupervisionCount }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 2: Status Seminar Proposal & Sidang Akhir -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <span class="fw-bold"><i class="bi bi-journal-text text-primary me-2"></i>Status Seminar Proposal</span>
                <span class="badge bg-indigo-subtle text-primary border">Sempro</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">Menunggu Penilaian</div>
                            <div class="fs-3 fw-extrabold text-primary">{{ $seminarStats['waitingAssessment'] ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">Seminar Selesai</div>
                            <div class="fs-3 fw-extrabold text-slate-700">{{ $seminarStats['finished'] ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-emerald-subtle rounded-3 border border-emerald-subtle">
                            <div class="text-success small fw-semibold">Lulus Seminar</div>
                            <div class="fs-3 fw-extrabold text-success">{{ $seminarStats['passed'] ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-rose-subtle rounded-3 border border-rose-subtle">
                            <div class="text-danger small fw-semibold">Seminar Ulang</div>
                            <div class="fs-3 fw-extrabold text-danger">{{ $seminarStats['repeat'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <span class="fw-bold"><i class="bi bi-mortarboard-fill text-primary me-2"></i>Status Sidang Akhir</span>
                <span class="badge bg-teal-subtle text-teal-emphasis border">Sidang</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">Pendaftaran Masuk</div>
                            <div class="fs-3 fw-extrabold text-primary">{{ $finalDefenseIncoming ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">Menunggu Penguji</div>
                            <div class="fs-3 fw-extrabold text-amber-600">{{ $finalDefenseWaitingExaminer ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">Siap Dijadwalkan</div>
                            <div class="fs-3 fw-extrabold text-indigo-600">{{ $finalDefenseReadyToSchedule ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-emerald-subtle rounded-3 border border-emerald-subtle">
                            <div class="text-success small fw-semibold">Selesai Sidang</div>
                            <div class="fs-3 fw-extrabold text-success">{{ $finalDefenseFinished ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 3: Tables Pengajuan & Bimbingan -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold py-3"><i class="bi bi-clock-history text-primary me-2"></i>Pengajuan Judul Terbaru</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Judul</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestProjects as $project)
                            <tr>
                                <td class="fw-semibold text-slate-800">{{ $project->student->nama }}</td>
                                <td class="text-truncate" style="max-width: 200px;">{{ $project->title }}</td>
                                <td><x-status-badge :status="$project->status" /></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada pengajuan terbaru.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold py-3"><i class="bi bi-person-exclamation text-amber-500 me-2"></i>Mahasiswa Belum Pembimbing</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Judul</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentsWithoutSupervision as $student)
                            <tr>
                                <td class="fw-semibold text-slate-800">{{ $student->nama }}</td>
                                <td><span class="badge bg-light text-dark border font-mono">{{ $student->nim }}</span></td>
                                <td class="text-truncate" style="max-width: 180px;">{{ $student->finalProject?->title ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4"><i class="bi bi-check-circle text-success me-1"></i> Semua mahasiswa sudah memiliki pembimbing.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Section 4: Agenda Seminar Proposal -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <span class="fw-bold"><i class="bi bi-calendar-range-fill text-primary me-2"></i>Agenda & Jadwal Seminar Proposal</span>
    </div>
    <div class="card-body">
        <form method="get" class="row g-3 mb-4 bg-light p-3 rounded-3 border">
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Ruangan</label>
                <select name="room_id" class="form-select">
                    <option value="">Semua Ruangan</option>
                    @foreach($roomsFilter as $r)
                        <option value="{{ $r->id }}" {{ isset($filters['room_id']) && $filters['room_id'] == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Dosen Pembimbing</label>
                <select name="lecturer_id" class="form-select">
                    <option value="">Semua Dosen</option>
                    @foreach($lecturersFilter as $l)
                        <option value="{{ $l->id }}" {{ isset($filters['lecturer_id']) && $filters['lecturer_id'] == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 align-self-end">
                <button class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
            </div>
        </form>

        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-calendar-event me-2"></i>Jadwal Hari Ini</h6>
        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Ruangan</th>
                        <th>Mahasiswa</th>
                        <th>Judul PA</th>
                        <th>Pembimbing 1</th>
                        <th>Pembimbing 2</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todaySchedules as $s)
                        <tr>
                            <td><span class="badge bg-light text-dark border">{{ $s->date }}</span></td>
                            <td class="font-mono text-nowrap">{{ $s->start_time }} - {{ $s->end_time }}</td>
                            <td><span class="badge bg-indigo-subtle text-primary border">{{ $s->room?->name ?? '-' }}</span></td>
                            <td class="fw-semibold">{{ $s->seminarProposal->finalProject->student->nama ?? '-' }}</td>
                            <td class="text-truncate" style="max-width: 180px;">{{ $s->seminarProposal->finalProject->title ?? '-' }}</td>
                            <td>{{ $s->seminarProposal->finalProject->supervisorOne()?->lecturer?->nama ?? '-' }}</td>
                            <td>{{ $s->seminarProposal->finalProject->supervisorTwo()?->lecturer?->nama ?? '-' }}</td>
                            <td><x-status-badge :status="$s->status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada jadwal seminar hari ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h6 class="fw-bold text-slate-700 mb-3"><i class="bi bi-calendar-plus me-2"></i>Jadwal Mendatang</h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Ruangan</th>
                        <th>Mahasiswa</th>
                        <th>Judul PA</th>
                        <th>Pembimbing 1</th>
                        <th>Pembimbing 2</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingSchedules as $s)
                        <tr>
                            <td><span class="badge bg-light text-dark border">{{ $s->date }}</span></td>
                            <td class="font-mono text-nowrap">{{ $s->start_time }} - {{ $s->end_time }}</td>
                            <td><span class="badge bg-indigo-subtle text-primary border">{{ $s->room?->name ?? '-' }}</span></td>
                            <td class="fw-semibold">{{ $s->seminarProposal->finalProject->student->nama ?? '-' }}</td>
                            <td class="text-truncate" style="max-width: 180px;">{{ $s->seminarProposal->finalProject->title ?? '-' }}</td>
                            <td>{{ $s->seminarProposal->finalProject->supervisorOne()?->lecturer?->nama ?? '-' }}</td>
                            <td>{{ $s->seminarProposal->finalProject->supervisorTwo()?->lecturer?->nama ?? '-' }}</td>
                            <td><x-status-badge :status="$s->status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada jadwal seminar mendatang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

