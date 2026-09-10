@extends('layouts.app', ['heading' => 'Update Progress'])

@section('content')
<form method="post" action="{{ route('student.progress.store') }}" class="card shadow-sm border-0" enctype="multipart/form-data">
    @csrf
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label">Jenis Progress</label>
            <select name="progress_type" class="form-select" required>
                @foreach(['PROPOSAL', 'DESIGN', 'IMPLEMENTATION', 'TESTING', 'REVISION_AFTER_SEMINAR', 'FINAL_REPORT'] as $type)
                    <option value="{{ $type }}" @selected(old('progress_type') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Persentase</label>
            <input type="number" name="percentage" class="form-control" value="{{ old('percentage', 0) }}" min="0" max="100" required>
        </div>
        <div class="col-12">
            <label class="form-label">Keterangan</label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label">Upload File</label>
            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
        </div>
    </div>
    <div class="card-footer bg-white text-end">
        <a class="btn btn-outline-secondary" href="{{ route('student.progress.index') }}">Batal</a>
        <button class="btn btn-primary">Kirim Progress</button>
    </div>
</form>
@endsection
