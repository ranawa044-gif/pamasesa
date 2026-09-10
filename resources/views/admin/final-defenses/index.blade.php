@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-kicker">Sidang Akhir</div>
            <h3 class="fw-bold">Daftar Sidang Akhir</h3>
        </div>
        <div>
            <a href="{{ route('admin.final-defenses.auto.form') }}" class="btn btn-primary">Auto Jadwalkan Sidang</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="text-muted mb-2">Daftar Sidang Masuk</div>
                <div class="h3 mb-0">{{ $finalDefenses->where('status', 'SUBMITTED')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="text-muted mb-2">Menunggu Penguji</div>
                <div class="h3 mb-0">{{ $finalDefenses->where('status', 'WAITING_EXAMINER')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="text-muted mb-2">Siap Dijadwalkan</div>
                <div class="h3 mb-0">{{ $finalDefenses->where('status', 'READY_TO_SCHEDULE')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="text-muted mb-2">Selesai Sidang</div>
                <div class="h3 mb-0">{{ $finalDefenses->whereIn('status', ['FINISHED', 'PASSED'])->count() }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Judul</th>
                        <th>Pembimbing</th>
                        <th>Status</th>
                        <th>Penguji</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($finalDefenses as $finalDefense)
                        <tr>
                            <td>{{ $finalDefense->finalProject->student->nama }}</td>
                            <td>{{ $finalDefense->finalProject->title }}</td>
                            <td>
                                @foreach($finalDefense->finalProject->supervisors as $supervisor)
                                    <div>{{ $supervisor->lecturer->nama }}</div>
                                @endforeach
                            </td>
                            <td><x-status-badge :status="$finalDefense->status" /></td>
                            <td>
                                @forelse($finalDefense->examiners as $examiner)
                                    <div>{{ $examiner->type }}: {{ $examiner->lecturer->nama }}</div>
                                @empty
                                    <span class="text-muted">Belum ditetapkan</span>
                                @endforelse
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#assign-{{ $finalDefense->id }}">
                                    Tetapkan Penguji
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="assign-{{ $finalDefense->id }}">
                            <td colspan="6">
                                <form method="post" action="{{ route('admin.final-defenses.assign', $finalDefense) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-5">
                                            <label class="form-label">Penguji 1</label>
                                            <select name="examiner_1_id" class="form-select">
                                                <option value="">Pilih Penguji 1</option>
                                                @foreach($lecturers as $lecturer)
                                                    <option value="{{ $lecturer->id }}" @selected(old('examiner_1_id') == $lecturer->id)>{{ $lecturer->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Penguji 2</label>
                                            <select name="examiner_2_id" class="form-select">
                                                <option value="">Pilih Penguji 2</option>
                                                @foreach($lecturers as $lecturer)
                                                    <option value="{{ $lecturer->id }}" @selected(old('examiner_2_id') == $lecturer->id)>{{ $lecturer->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary w-100">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
