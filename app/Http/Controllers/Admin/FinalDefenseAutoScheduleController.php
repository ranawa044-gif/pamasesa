<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinalDefenseAutoScheduleRequest;
use App\Models\Room;
use App\Services\FinalDefenseSchedulerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinalDefenseAutoScheduleController extends Controller
{
    public function form(): View
    {
        $rooms = Room::orderBy('name')->get();

        return view('admin.final-defenses.auto_form', compact('rooms'));
    }

    public function preview(FinalDefenseAutoScheduleRequest $request, FinalDefenseSchedulerService $service): View
    {
        $data = $request->validated();
        $data['room_ids'] = $request->input('room_ids', []);
        $data['regenerate'] = $request->boolean('regenerate');

        $result = $service->generate($data);

        return view('admin.final-defenses.auto_preview', [
            'result' => $result,
            'params' => $data,
            'rooms' => Room::whereIn('id', $data['room_ids'])->get(),
        ]);
    }

    public function publish(Request $request, FinalDefenseSchedulerService $service)
    {
        $assignments = json_decode($request->input('assignments', '[]'), true) ?: [];

        try {
            $created = $service->publish($assignments);
        } catch (\Exception $e) {
            return back()->withErrors(['publish' => $e->getMessage()])->withInput();
        }

        return redirect()->route('admin.final-defenses.index')->with('success', 'Jadwal sidang akhir otomatis berhasil dipublikasikan. Total: ' . count($created));
    }
}
