@extends('layouts.app', ['heading' => 'Jadwalkan Seminar'])

@section('content')
<form method="post" action="{{ route('admin.seminars.schedule.store') }}" class="card shadow-sm border-0">
    @csrf
    <input type="hidden" name="seminar_proposal_id" value="{{ $seminarProposal->id }}">
    <div class="card-body row g-3">
        <div class="col-md-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Jam Mulai</label>
            <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Jam Selesai</label>
            <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Ruangan</label>
            <select name="room_id" class="form-select" required>
                <option value="">Pilih Ruangan</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="card-footer bg-white text-end">
        <a class="btn btn-outline-secondary" href="{{ route('admin.seminars.index') }}">Batal</a>
        <button class="btn btn-primary">Simpan Jadwal</button>
    </div>
</form>
@endsection
