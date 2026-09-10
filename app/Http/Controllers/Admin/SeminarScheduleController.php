<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleSeminarRequest;
use App\Models\Room;
use App\Models\SeminarProposal;
use App\Models\SeminarSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeminarScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $proposals = SeminarProposal::with('finalProject.student', 'schedule.room')
            ->orderByDesc('created_at')
            ->get();

        $readyCount = SeminarProposal::whereIn('status', ['APPROVED_BY_SUPERVISORS', 'READY_TO_SCHEDULE'])->count();
        $scheduledCount = SeminarProposal::where('status', 'SCHEDULED')->count();
        $notScheduledCount = max(0, $readyCount - $scheduledCount);

        return view('admin.seminars.index', compact('proposals', 'readyCount', 'scheduledCount', 'notScheduledCount'));
    }

    public function create(SeminarProposal $seminarProposal): View
    {
        abort_unless(in_array($seminarProposal->status, ['READY_TO_SCHEDULE', 'APPROVED_BY_SUPERVISORS']), 403, 'Seminar belum siap dijadwalkan.');

        $rooms = Room::orderBy('name')->get();

        return view('admin.seminars.form', compact('seminarProposal', 'rooms'));
    }

    public function store(ScheduleSeminarRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $proposal = SeminarProposal::with('finalProject.supervisors')->findOrFail($data['seminar_proposal_id']);

        abort_unless(in_array($proposal->status, ['READY_TO_SCHEDULE', 'APPROVED_BY_SUPERVISORS']), 403, 'Seminar belum siap dijadwalkan.');

        $errors = [];
        $supervisorIds = $proposal->finalProject->supervisors->pluck('lecturer_id')->filter()->all();

        // existing schedule for same proposal
        $existingProposal = SeminarSchedule::where('seminar_proposal_id', $proposal->id)
            ->whereIn('status', ['DRAFT', 'PUBLISHED'])
            ->exists();
        if ($existingProposal) {
            $errors[] = 'Mahasiswa sudah memiliki jadwal seminar proposal.';
        }

        // existing schedule for same student
        $existingStudent = SeminarSchedule::whereHas('seminarProposal', function ($query) use ($proposal) {
                $query->where('final_project_id', $proposal->final_project_id);
            })
            ->whereIn('status', ['DRAFT', 'PUBLISHED'])
            ->exists();
        if ($existingStudent) {
            $errors[] = 'Mahasiswa sudah memiliki jadwal seminar proposal.';
        }

        $roomConflict = SeminarSchedule::where('date', $data['date'])
            ->where('room_id', $data['room_id'])
            ->whereIn('status', ['DRAFT', 'PUBLISHED'])
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->exists();
        if ($roomConflict) {
            $errors[] = 'Ruangan sudah digunakan pada waktu tersebut.';
        }

        $supervisorConflict = SeminarSchedule::where('date', $data['date'])
            ->whereIn('status', ['DRAFT', 'PUBLISHED'])
            ->whereHas('seminarProposal.finalProject.supervisors', function ($query) use ($supervisorIds) {
                $query->whereIn('lecturer_id', $supervisorIds);
            })
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->exists();
        if ($supervisorConflict) {
            $errors[] = 'Pembimbing sudah memiliki seminar pada jam tersebut.';
        }

        if (! empty($errors)) {
            $message = 'Jadwal tidak dapat dibuat karena terdapat konflik jadwal.';
            $details = implode(' ', array_unique($errors));

            return back()->withErrors(['schedule' => $message . ' ' . $details])->withInput();
        }

        SeminarSchedule::create([
            'seminar_proposal_id' => $data['seminar_proposal_id'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'room_id' => $data['room_id'],
            'status' => 'PUBLISHED',
        ]);

        $proposal->update(['status' => 'SCHEDULED']);

        return redirect()->route('admin.seminars.index')->with('success', 'Seminar berhasil dijadwalkan.');
    }
}
