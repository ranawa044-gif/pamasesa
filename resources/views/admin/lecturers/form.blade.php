@extends('layouts.app', ['heading' => $lecturer->exists ? 'Edit Dosen' : 'Tambah Dosen'])

@section('content')
<form method="post" action="{{ $lecturer->exists ? route('admin.lecturers.update', $lecturer) : route('admin.lecturers.store') }}" class="card shadow-sm border-0">
    @csrf
    @if($lecturer->exists) @method('PUT') @endif
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label">Nama User</label>
            <input name="name" class="form-control" value="{{ old('name', $lecturer->user?->name) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $lecturer->user?->email) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Password {{ $lecturer->exists ? '(kosongkan jika tidak diubah)' : '' }}</label>
            <input type="password" name="password" class="form-control" {{ $lecturer->exists ? '' : 'required' }}>
        </div>
        <div class="col-md-6">
            <label class="form-label">NIDN</label>
            <input name="nidn" class="form-control" value="{{ old('nidn', $lecturer->nidn) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nama Dosen</label>
            <input name="nama" class="form-control" value="{{ old('nama', $lecturer->nama) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Bidang Keahlian</label>
            <input name="bidang_keahlian" class="form-control" value="{{ old('bidang_keahlian', $lecturer->bidang_keahlian) }}">
        </div>
    </div>
    <div class="card-footer bg-white text-end">
        <a class="btn btn-outline-secondary" href="{{ route('admin.lecturers.index') }}">Batal</a>
        <button class="btn btn-primary">Simpan</button>
    </div>
</form>
@endsection
