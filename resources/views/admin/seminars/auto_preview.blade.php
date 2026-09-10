@extends('layouts.app', ['heading' => 'Preview Jadwal Otomatis'])

@section('content')
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <h5 class="mb-3">Ringkasan</h5>
        <div>Slot total: {{ $result['slots_count'] }}</div>
        <div>Berhasil dijadwalkan: {{ count($result['assigned']) }}</div>
        <div>Gagal: {{ count($result['failed']) }}</div>
        @if(!empty($params['break_start']) && !empty($params['break_end']))
            <div>Istirahat: {{ $params['break_start'] }} - {{ $params['break_end'] }}</div>
        @else
            <div>Istirahat: {{ $params['break_minutes'] ?? 0 }} menit</div>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead><tr><th>Mahasiswa</th><th>Judul</th><th>Tanggal</th><th>Waktu</th><th>Ruangan</th></tr></thead>
            <tbody>
                @foreach($result['assigned'] as $item)
                    <tr>
                        <td>{{ $item['proposal']->finalProject->student->nama }}</td>
                        <td>{{ $item['proposal']->finalProject->title }}</td>
                        <td>{{ $item['date'] }}</td>
                        <td>{{ $item['start_time'] }}-{{ $item['end_time'] }}</td>
                        <td>{{ $rooms->firstWhere('id', $item['room_id'])->name ?? $item['room_id'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(count($result['failed']))
        <div class="card-body">
            <h6>Gagal Dijadwalkan</h6>
            <ul>
                @foreach($result['failed'] as $f)
                    <li>{{ $f['proposal']->finalProject->student->nama }}: {{ $f['reason'] }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card-footer bg-white text-end">
        <form method="post" action="{{ route('admin.seminars.auto.publish') }}" class="d-inline">
            @csrf
            <input type="hidden" name="assignments" value='{{ json_encode(array_map(function($a){ return ["proposal_id" => $a["proposal_id"], "date" => $a["date"], "start_time" => $a["start_time"], "end_time" => $a["end_time"], "room_id" => $a["room_id"]]; }, $result["assigned"])) }}'>
            <button class="btn btn-success">Publish Jadwal ({{ count($result['assigned']) }})</button>
        </form>
        <a class="btn btn-outline-secondary" href="{{ route('admin.seminars.auto.form') }}">Kembali</a>
    </div>
</div>
@endsection
