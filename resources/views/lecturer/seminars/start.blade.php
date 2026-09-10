@extends('layouts.app', ['heading' => 'Mulai Seminar Proposal'])

@section('content')
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h5>{{ $seminarSchedule->seminarProposal->finalProject->student->nama }} - {{ $seminarSchedule->seminarProposal->finalProject->title }}</h5>
                <p>NIM: {{ $seminarSchedule->seminarProposal->finalProject->student->nim }}</p>
                <p>Pembimbing 1: {{ $seminarSchedule->seminarProposal->finalProject->supervisorOne()?->lecturer?->nama ?? '-' }}</p>
                <p>Pembimbing 2: {{ $seminarSchedule->seminarProposal->finalProject->supervisorTwo()?->lecturer?->nama ?? '-' }}</p>
                <p>Mahasiswa membuka proposal: <a href="{{ asset('storage/' . $seminarSchedule->seminarProposal->proposal_file) }}" target="_blank">Lihat dokumen proposal</a></p>
                <p>Jadwal: {{ $seminarSchedule->date }} {{ $seminarSchedule->start_time }}-{{ $seminarSchedule->end_time }}</p>
                <p>Ruangan: {{ $seminarSchedule->room?->name ?? '-' }}</p>
                <p>Status seminar: <x-status-badge :status="$seminarSchedule->status" /></p>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h5>Form Penilaian</h5>
                <form method="post" action="{{ route('lecturer.seminars.assess', $seminarSchedule) }}">
                    @csrf
                    <div class="row g-3">
                        @foreach(['problem' => 'Kejelasan Masalah', 'method' => 'Metode Penelitian / Pengembangan', 'design' => 'Perancangan Sistem', 'implementation' => 'Kesiapan Implementasi', 'presentation' => 'Presentasi dan Pemahaman'] as $key => $label)
                        @php $selectedScore = optional($assessment)->{$key . '_score'}; @endphp
                        <div class="col-md-6">
                            <label class="form-label">{{ $label }}</label>
                            <select name="{{ $key }}_score" class="form-select" required>
                                @for($score = 1; $score <= 5; $score++)
                                    <option value="{{ $score }}" @selected($selectedScore == $score)>{{ $score }}</option>
                                @endfor
                            </select>
                            @error($key . '_score')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Keputusan</label>
                            <select name="decision" class="form-select" required>
                                <option value="PASSED" @selected(optional($assessment)->decision === 'PASSED')>PASSED</option>
                                <option value="PASSED_WITH_REVISION" @selected(optional($assessment)->decision === 'PASSED_WITH_REVISION')>PASSED_WITH_REVISION</option>
                                <option value="REPEAT" @selected(optional($assessment)->decision === 'REPEAT')>REPEAT</option>
                            </select>
                            @error('decision')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Catatan Revisi</label>
                            <textarea name="revision_note" class="form-control" rows="3">{{ old('revision_note', optional($assessment)->revision_note) }}</textarea>
                            @error('revision_note')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button class="btn btn-primary mt-3">Simpan Penilaian</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5>Catatan Revisi Digital</h5>
                <form method="post" action="{{ route('lecturer.seminars.revisions.store', $seminarSchedule) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <select name="revision_category" class="form-select" required>
                                <option value="BAB_1" @selected(old('revision_category') === 'BAB_1')>BAB 1</option>
                                <option value="BAB_2" @selected(old('revision_category') === 'BAB_2')>BAB 2</option>
                                <option value="BAB_3" @selected(old('revision_category') === 'BAB_3')>BAB 3</option>
                                <option value="SYSTEM" @selected(old('revision_category') === 'SYSTEM')>SYSTEM</option>
                                <option value="OTHER" @selected(old('revision_category') === 'OTHER')>LAINNYA</option>
                            </select>
                            @error('revision_category')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Catatan</label>
                            <textarea name="revision_note" class="form-control" rows="3" required>{{ old('revision_note') }}</textarea>
                            @error('revision_note')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-outline-primary mt-3">Tambah Catatan Revisi</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Ringkasan Seminar</h5>
                <p>Status Seminar: <x-status-badge :status="$seminarSchedule->status" /></p>
                <p>Proposal: <a href="{{ asset('storage/' . $seminarSchedule->seminarProposal->proposal_file) }}" target="_blank">Lihat</a></p>
                <p class="mb-1">Nilai guru:</p>
                @forelse($seminarSchedule->assessments as $assessment)
                    <div class="mb-2 border rounded p-2">
                        <div class="fw-semibold">{{ $assessment->lecturer->nama }}</div>
                        <div>Nilai akhir: {{ $assessment->total_score }} / 100</div>
                        <div>Keputusan: <x-status-badge :status="$assessment->decision" /></div>
                    </div>
                @empty
                    <div class="text-muted">Belum ada penilaian.</div>
                @endforelse
                @if($finalDecision)
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="fw-semibold">Keputusan final:</div>
                        <div><x-status-badge :status="$finalDecision" /></div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5>Daftar Revisi</h5>
                <ul class="list-group list-group-flush">
                    @forelse($seminarSchedule->revisions as $revision)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">{{ $revision->revision_category }}</div>
                                    <div>{{ $revision->revision_note }}</div>
                                    <div class="text-muted small">Dari: {{ $revision->lecturer->nama }}</div>
                                    @if($revision->student_note)
                                        <div class="mt-2"><strong>Catatan Mahasiswa:</strong> {{ $revision->student_note }}</div>
                                    @endif
                                    @if($revision->student_file_path)
                                        <div class="mt-1"><a href="{{ asset('storage/' . $revision->student_file_path) }}" target="_blank">Lihat bukti revisi mahasiswa</a></div>
                                    @endif
                                    @if($revision->validation_note)
                                        <div class="mt-2"><strong>Catatan Validasi:</strong> {{ $revision->validation_note }}</div>
                                    @endif
                                </div>
                                <span class="badge bg-{{ $revision->status === 'DONE' ? 'success' : ($revision->status === 'WAITING_VALIDATION' ? 'info' : 'warning') }}">{{ $revision->status }}</span>
                            </div>

                            @if($revision->status === 'WAITING_VALIDATION')
                                <form method="post" action="{{ route('lecturer.seminars.revisions.validate', $revision) }}" class="mt-3" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label">Status Validasi</label>
                                            <select name="status" class="form-select" required>
                                                <option value="DONE">DONE</option>
                                                <option value="REVISION">REVISION</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Catatan Validasi</label>
                                            <textarea name="validation_note" class="form-control" rows="2" placeholder="Catatan dari pembimbing"></textarea>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button class="btn btn-sm btn-primary">Simpan Validasi</button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Belum ada catatan revisi.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
