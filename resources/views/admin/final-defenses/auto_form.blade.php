@extends('layouts.app', ['heading' => 'Auto Jadwal Sidang Akhir'])

@section('content')
<form method="post" action="{{ route('admin.final-defenses.auto.preview') }}" class="card shadow-sm border-0">
    @csrf
    <div class="card-body row g-3">
        <div class="col-md-3">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Jam Mulai</label>
            <input type="time" name="start_time" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Jam Selesai</label>
            <input type="time" name="end_time" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Durasi (menit)</label>
            <input type="number" name="duration_minutes" class="form-control" value="60" min="10" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Mulai Istirahat</label>
            <input type="time" name="break_start" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label">Selesai Istirahat</label>
            <input type="time" name="break_end" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label">Istirahat (menit)</label>
            <input type="number" name="break_minutes" class="form-control" value="15" min="0" required>
        </div>
        <div class="col-12">
            <label class="form-label">Pilih Ruangan</label>
            <select name="room_ids[]" class="form-select" multiple size="6" required>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="regenerate" value="1" id="regenerateCheck">
                <label class="form-check-label" for="regenerateCheck">Regenerate jadwal (hapus jadwal DRAFT lama dalam periode)</label>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white text-end">
        <a class="btn btn-outline-secondary" href="{{ route('admin.final-defenses.index') }}">Batal</a>
        <button class="btn btn-primary">Preview Jadwal</button>
    </div>
</form>
@endsection
