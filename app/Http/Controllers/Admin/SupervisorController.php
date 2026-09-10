<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignSupervisorRequest;
use App\Models\FinalProject;
use App\Models\Lecturer;
use App\Models\Supervisor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupervisorController extends Controller
{
    public function index(): View
    {
        return view('admin.supervisors.index', [
            'projects' => FinalProject::with(['student', 'supervisors.lecturer'])
                ->approved()
                ->latest()
                ->paginate(10),
        ]);
    }

    public function edit(FinalProject $finalProject): View
    {
        abort_unless($finalProject->status === 'APPROVED', 404);

        $finalProject->load('student', 'supervisors.lecturer');

        return view('admin.supervisors.form', [
            'finalProject' => $finalProject,
            'lecturers' => Lecturer::orderBy('nama')->get(),
        ]);
    }

    public function update(AssignSupervisorRequest $request, FinalProject $finalProject): RedirectResponse
    {
        abort_unless($finalProject->status === 'APPROVED', 404);

        DB::transaction(function () use ($request, $finalProject): void {
            $finalProject->supervisors()->delete();

            Supervisor::create([
                'final_project_id' => $finalProject->id,
                'lecturer_id' => $request->pembimbing_1_id,
                'type' => 'PEMBIMBING_1',
                'assigned_at' => now(),
            ]);

            Supervisor::create([
                'final_project_id' => $finalProject->id,
                'lecturer_id' => $request->pembimbing_2_id,
                'type' => 'PEMBIMBING_2',
                'assigned_at' => now(),
            ]);
        });

        return redirect()->route('admin.supervisors.index')->with('success', 'Pembimbing berhasil ditentukan.');
    }
}
