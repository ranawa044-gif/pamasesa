<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgressLogRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProgressLogController extends Controller
{
    public function index(Request $request): View
    {
        $finalProject = $request->user()->student?->finalProject()->with('progressLogs')->first();

        return view('student.progress.index', compact('finalProject'));
    }

    public function create(Request $request): View
    {
        $finalProject = $request->user()->student?->finalProject;
        abort_unless(
            $finalProject?->status === 'APPROVED' || $finalProject?->status === 'READY_FOR_DEFENSE' || $finalProject?->seminarProposal?->status === 'SEMINAR_PASSED',
            403,
            'Progress dapat diisi setelah judul ACC atau setelah seminar proposal lulus.'
        );

        return view('student.progress.form', compact('finalProject'));
    }

    public function store(StoreProgressLogRequest $request): RedirectResponse
    {
        $finalProject = $request->user()->student?->finalProject;
        abort_unless(
            $finalProject?->status === 'APPROVED' || $finalProject?->status === 'READY_FOR_DEFENSE' || $finalProject?->seminarProposal?->status === 'SEMINAR_PASSED',
            403,
            'Progress dapat diisi setelah judul ACC atau setelah seminar proposal lulus.'
        );

        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('guidance/submissions', 'public');
        }

        $finalProject->progressLogs()->create($data + [
            'status' => 'WAITING',
            'review_note' => null,
        ]);

        return redirect()->route('student.progress.index')->with('success', 'Progress berhasil dikirim.');
    }
}
