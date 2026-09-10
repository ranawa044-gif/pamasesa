@extends('layouts.app', ['heading' => 'Tentukan Pembimbing'])

@section('content')
<form method="post" action="{{ route('admin.supervisors.update', $finalProject) }}" class="card shadow-sm border-0">
    @csrf @method('PUT')
    <div class="card-body">
        <h3 class="h5">{{ $finalProject->title }}</h3>
        <p class="text-muted">{{ $finalProject->student->nama }} - {{ $finalProject->student->nim }}</p>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Pembimbing 1</label>
                <select name="pembimbing_1_id" class="form-select" required>
                    <option value="">Pilih dosen</option>
                    @foreach($lecturers as $lecturer)
                        <option value="{{ $lecturer->id }}" @selected(old('pembimbing_1_id', $finalProject->supervisorOne()?->lecturer_id) == $lecturer->id)>{{ $lecturer->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Pembimbing 2</label>
                <select name="pembimbing_2_id" class="form-select" required>
                    <option value="">Pilih dosen</option>
                    @foreach($lecturers as $lecturer)
                        <option value="{{ $lecturer->id }}" @selected(old('pembimbing_2_id', $finalProject->supervisorTwo()?->lecturer_id) == $lecturer->id)>{{ $lecturer->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white text-end">
        <a class="btn btn-outline-secondary" href="{{ route('admin.supervisors.index') }}">Batal</a>
        <button class="btn btn-primary">Simpan Pembimbing</button>
    </div>
</form>
@endsection
