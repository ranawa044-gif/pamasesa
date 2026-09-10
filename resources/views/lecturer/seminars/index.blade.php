@extends('layouts.app', ['heading' => 'Pengajuan Seminar Mahasiswa'])

@section('content')
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Mahasiswa</th><th>Judul</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    @if($project->seminarProposal)
                    <tr>
                        <td>{{ $project->student->nama }}</td>
                        <td>{{ $project->title }}</td>
                        <td><x-status-badge :status="$project->seminarProposal->status" /></td>
                        <td class="text-end"><a class="btn btn-sm btn-primary" href="{{ route('lecturer.seminars.show', $project->seminarProposal) }}">Detail</a></td>
                    </tr>
                    @endif
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada pengajuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
