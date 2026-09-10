@extends('layouts.app', ['heading' => 'Daftar Seminar Proposal'])

@section('content')
<form method="post" action="{{ route('student.seminars.store') }}" enctype="multipart/form-data" class="card shadow-sm border-0">
    @csrf
    <div class="card-body">
        <input type="hidden" name="final_project_id" value="{{ $finalProject->id }}">
        <div class="mb-3">
            <label class="form-label">Upload Proposal (PDF)</label>
            <input type="file" name="proposal_file" class="form-control" accept="application/pdf" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Catatan (opsional)</label>
            <textarea name="student_note" class="form-control" rows="3">{{ old('student_note') }}</textarea>
        </div>
    </div>
    <div class="card-footer bg-white text-end">
        <a class="btn btn-outline-secondary" href="{{ route('student.seminars.index') }}">Batal</a>
        <button class="btn btn-primary">Kirim Pengajuan</button>
    </div>
</form>
@endsection
