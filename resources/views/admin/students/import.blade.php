@extends('layouts.app', ['heading' => 'Import Mahasiswa'])

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="post" action="{{ route('admin.students.import') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">File Excel / CSV</label>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                @error('file')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <p>Gunakan template berikut untuk mengimpor data.</p>
                <a class="btn btn-outline-primary" href="{{ route('admin.students.import.template') }}">Download Template</a>
            </div>

            <button class="btn btn-primary">Import</button>
            <a class="btn btn-link" href="{{ route('admin.students.index') }}">Kembali</a>
        </form>
    </div>
</div>
@endsection
