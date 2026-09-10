<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AutoScheduleRequest;
use App\Models\Room;
use App\Services\SeminarSchedulerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeminarAutoScheduleController extends Controller
{
    public function form(): View
    {
        $rooms = Room::orderBy('name')->get();
        return view('admin.seminars.auto_form', compact('rooms'));
    }

    public function preview(AutoScheduleRequest $request, SeminarSchedulerService $service): View
    {
        $data = $request->validated();
        $data['room_ids'] = $request->input('room_ids', []);
        $data['regenerate'] = $request->boolean('regenerate');

        $result = $service->generate($data);

        return view('admin.seminars.auto_preview', [
            'result' => $result,
            'params' => $data,
            'rooms' => Room::whereIn('id', $data['room_ids'])->get(),
        ]);
    }

    public function publish(Request $request, SeminarSchedulerService $service)
    {
        $assignments = json_decode($request->input('assignments', '[]'), true) ?: [];

        try {
            $created = $service->publish($assignments);
        } catch (\Exception $e) {
            return back()->withErrors(['publish' => $e->getMessage()])->withInput();
        }

        return redirect()->route('admin.seminars.index')->with('success', 'Jadwal seminar otomatis berhasil dipublikasikan. Total: '.count($created));
    }
}
