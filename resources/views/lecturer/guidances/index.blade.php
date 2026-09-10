@extends('layouts.app', ['heading' => 'Mahasiswa Bimbingan'])

@section('content')
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Mahasiswa</th><th>Judul</th><th>Status Judul</th><th>Progress Terakhir</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td>{{ $project->student->nama }}</td>
                        <td>{{ $project->title }}</td>
                        <td><x-status-badge :status="$project->status" /></td>
                        <td>{{ $project->progressLogs->first()?->percentage ?? 0 }}%</td>
                        <td class="text-end"><a class="btn btn-sm btn-primary" href="{{ route('lecturer.guidances.show', $project) }}">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada mahasiswa bimbingan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
