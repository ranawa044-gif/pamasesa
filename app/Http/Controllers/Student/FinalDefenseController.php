<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFinalDefenseRequest;
use App\Models\FinalDefense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinalDefenseController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        $finalProject = $student?->finalProject;
        $finalDefense = $finalProject?->finalDefense;

        return view('student.final-defenses.index', [
            'finalProject' => $finalProject,
            'finalDefense' => $finalDefense,
        ]);
    }

    public function store(StoreFinalDefenseRequest $request)
    {
        $student = $request->user()->student;
        $finalProject = $student?->finalProject;

        if (! $finalProject || $finalProject->status !== 'READY_FOR_DEFENSE') {
            return back()->withErrors(['final_project' => 'Anda belum memenuhi syarat untuk mendaftar sidang akhir.']);
        }

        if ($finalProject->finalDefense && in_array($finalProject->finalDefense->status, ['SUBMITTED', 'WAITING_EXAMINER', 'READY_TO_SCHEDULE', 'SCHEDULED', 'REVISION'])) {
            return back()->withErrors(['final_defense' => 'Anda sudah memiliki pendaftaran sidang yang sedang diproses.']);
        }

        $finalReportPath = $request->file('final_report_file')->store('final_defenses/reports', 'public');
        $applicationFile = $request->filled('application_link')
            ? $request->input('application_link')
            : $request->file('application_upload')->store('final_defenses/applications', 'public');

        FinalDefense::create([
            'final_project_id' => $finalProject->id,
            'final_report_file' => $finalReportPath,
            'application_file' => $applicationFile,
            'status' => 'SUBMITTED',
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.final-defenses.index')->with('success', 'Pendaftaran sidang akhir berhasil dikirim. Menunggu penetapan penguji.');
    }
}
