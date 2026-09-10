@extends('layouts.app', ['heading' => 'Master Dosen'])

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a class="btn btn-secondary me-2" href="{{ route('admin.lecturers.import.form') }}">Import Dosen</a>
    <a class="btn btn-primary" href="{{ route('admin.lecturers.create') }}">Tambah Dosen</a>
</div>
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>NIDN</th><th>Nama</th><th>Email</th><th>Bidang Keahlian</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($lecturers as $lecturer)
                    <tr>
                        <td>{{ $lecturer->nidn }}</td>
                        <td>{{ $lecturer->nama }}</td>
                        <td>{{ $lecturer->user->email }}</td>
                        <td>{{ $lecturer->bidang_keahlian ?? '-' }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.lecturers.edit', $lecturer) }}">Edit</a>
                            <form method="post" action="{{ route('admin.lecturers.destroy', $lecturer) }}" class="d-inline" onsubmit="return confirm('Hapus data dosen ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data dosen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $lecturers->links() }}</div>
@endsection
