@extends('layouts.app', ['heading' => 'Master Mahasiswa'])

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a class="btn btn-secondary me-2" href="{{ route('admin.students.import.form') }}">Import Mahasiswa</a>
    <a class="btn btn-primary" href="{{ route('admin.students.create') }}">Tambah Mahasiswa</a>
</div>
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>NIM</th><th>Nama</th><th>Email</th><th>Kelas</th><th>No. HP</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->nim }}</td>
                        <td>{{ $student->nama }}</td>
                        <td>{{ $student->user->email }}</td>
                        <td>{{ $student->kelas }}</td>
                        <td>{{ $student->phone ?? '-' }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.students.edit', $student) }}">Edit</a>
                            <form method="post" action="{{ route('admin.students.destroy', $student) }}" class="d-inline" onsubmit="return confirm('Hapus data mahasiswa ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data mahasiswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $students->links() }}</div>
@endsection
