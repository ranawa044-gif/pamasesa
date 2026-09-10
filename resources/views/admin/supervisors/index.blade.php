@extends('layouts.app', ['heading' => 'Penentuan Pembimbing'])

@section('content')
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Mahasiswa</th><th>Judul</th><th>Pembimbing 1</th><th>Pembimbing 2</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td>{{ $project->student->nama }}</td>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->supervisorOne()?->lecturer?->nama ?? '-' }}</td>
                        <td>{{ $project->supervisorTwo()?->lecturer?->nama ?? '-' }}</td>
                        <td class="text-end"><a class="btn btn-sm btn-primary" href="{{ route('admin.supervisors.edit', $project) }}">Tentukan</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada judul ACC.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $projects->links() }}</div>
@endsection
