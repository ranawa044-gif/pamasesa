<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignFinalDefenseExaminersRequest;
use App\Models\Examiner;
use App\Models\FinalDefense;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class FinalDefenseController extends Controller
{
    public function index(Request $request)
    {
        $finalDefenses = FinalDefense::with(['finalProject.student', 'finalProject.supervisors.lecturer', 'examiners.lecturer'])->latest()->get();
        $lecturers = Lecturer::orderBy('nama')->get();

        return view('admin.final-defenses.index', [
            'finalDefenses' => $finalDefenses,
            'lecturers' => $lecturers,
        ]);
    }

    public function assign(AssignFinalDefenseExaminersRequest $request, FinalDefense $finalDefense)
    {
        $supervisorIds = $finalDefense->finalProject->supervisors->pluck('lecturer_id')->toArray();
        $examiner1Id = $request->input('examiner_1_id');
        $examiner2Id = $request->input('examiner_2_id');

        if ($examiner1Id === $examiner2Id) {
            return back()->withErrors(['examiner_2_id' => 'Penguji 1 dan Penguji 2 harus berbeda.']);
        }

        if (in_array($examiner1Id, $supervisorIds) || in_array($examiner2Id, $supervisorIds)) {
            return back()->withErrors(['examiner_1_id' => 'Dosen penguji tidak boleh sama dengan pembimbing.']);
        }

        foreach (['PENGUJI_1' => $examiner1Id, 'PENGUJI_2' => $examiner2Id] as $type => $lecturerId) {
            Examiner::updateOrCreate(
                ['final_defense_id' => $finalDefense->id, 'type' => $type],
                ['lecturer_id' => $lecturerId, 'assigned_at' => now()]
            );
        }

        $finalDefense->update(['status' => 'READY_TO_SCHEDULE']);

        return back()->with('success', 'Penguji sidang akhir berhasil ditetapkan.');
    }
}
