<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitFinalProjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinalProjectController extends Controller
{
    public function edit(Request $request): View
    {
        $student = $request->user()->student;
        $finalProject = $student?->finalProject;
        $lockedSubmission = in_array($finalProject?->status, ['SUBMITTED', 'REVIEW', 'APPROVED'], true);

        return view('student.final-projects.form', compact('student', 'finalProject', 'lockedSubmission'));
    }

    public function store(SubmitFinalProjectRequest $request): RedirectResponse
    {
        $student = $request->user()->student;
        abort_unless($student, 403, 'Profil mahasiswa belum tersedia.');

        $existingProject = $student->finalProject;
        if (in_array($existingProject?->status, ['SUBMITTED', 'REVIEW', 'APPROVED'], true)) {
            return redirect()
                ->route('student.final-project.edit')
                ->withErrors(['title' => 'Pengajuan judul tidak dapat diubah sebelum admin memberi keputusan atau setelah judul ACC.']);
        }

        $student->update($request->safe()->only(['phone', 'guardian_phone']));

        $data = $request->safe()->except([
            'phone',
            'guardian_phone',
            'development_method_other',
            'additional_method_other',
            'testing_plan_other',
        ]);

        if (($data['development_method'] ?? null) === 'Lainnya' && $request->filled('development_method_other')) {
            $data['development_method'] = $request->development_method_other;
        }

        $additionalMethods = $data['additional_method'] ?? [];
        if ($request->filled('additional_method_other')) {
            $additionalMethods[] = $request->additional_method_other;
        }

        $testingPlans = $data['testing_plan'] ?? [];
        if ($request->filled('testing_plan_other')) {
            $testingPlans[] = $request->testing_plan_other;
        }

        $data['additional_method'] = implode(', ', $additionalMethods);
        $data['testing_plan'] = implode(', ', $testingPlans);
        $data['declaration'] = true;

        $student->finalProject()->updateOrCreate(
            ['student_id' => $student->id],
            $data + [
                'status' => 'SUBMITTED',
                'review_note' => null,
                'submitted_at' => now(),
                'approved_at' => null,
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Judul Proyek Akhir berhasil diajukan.');
    }
}
