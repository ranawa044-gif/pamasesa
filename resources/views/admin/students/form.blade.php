@extends('layouts.app', ['heading' => $student->exists ? 'Edit Mahasiswa' : 'Tambah Mahasiswa'])

@section('content')
<form method="post" action="{{ $student->exists ? route('admin.students.update', $student) : route('admin.students.store') }}" class="card shadow-sm border-0">
    @csrf
    @if($student->exists) @method('PUT') @endif
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label">Nama User</label>
            <input name="name" class="form-control" value="{{ old('name', $student->user?->name) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $student->user?->email) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Password {{ $student->exists ? '(kosongkan jika tidak diubah)' : '' }}</label>
            <input type="password" name="password" class="form-control" {{ $student->exists ? '' : 'required' }}>
        </div>
        <div class="col-md-6">
            <label class="form-label">NIM</label>
            <input name="nim" class="form-control" value="{{ old('nim', $student->nim) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nama Mahasiswa</label>
            <input name="nama" class="form-control" value="{{ old('nama', $student->nama) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Angkatan</label>
            <input type="number" name="angkatan" class="form-control" value="{{ old('angkatan', $student->angkatan) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Kelas</label>
            <input name="kelas" class="form-control" value="{{ old('kelas', $student->kelas) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">No. HP (Whatsapp)</label>
            <input name="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">No. HP Orang Tua/Kerabat</label>
            <input name="guardian_phone" class="form-control" value="{{ old('guardian_phone', $student->guardian_phone) }}">
        </div>
    </div>
    <div class="card-footer bg-white text-end">
        <a class="btn btn-outline-secondary" href="{{ route('admin.students.index') }}">Batal</a>
        <button class="btn btn-primary">Simpan</button>
    </div>
</form>
@endsection
