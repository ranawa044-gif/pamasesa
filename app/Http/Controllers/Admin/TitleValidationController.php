<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewFinalProjectRequest;
use App\Models\FinalProject;
use App\Models\TitleReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TitleValidationController extends Controller
{
    public function index(): View
    {
        return view('admin.titles.index', [
            'projects' => FinalProject::with('student')
                ->where('status', '!=', 'DRAFT')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function show(FinalProject $finalProject): View
    {
        $finalProject->load(['student', 'titleReview']);

        return view('admin.titles.show', compact('finalProject'));
    }

    public function update(ReviewFinalProjectRequest $request, FinalProject $finalProject): RedirectResponse
    {
        $scores = [
            'problem_score' => $request->input('problem_score'),
            'solution_score' => $request->input('solution_score'),
            'complexity_score' => $request->input('complexity_score'),
            'method_score' => $request->input('method_score'),
            'testing_score' => $request->input('testing_score'),
        ];

        $totalScore = array_sum($scores);
        $decision = $totalScore >= 40 ? 'APPROVED' : ($totalScore >= 30 ? 'REVISION' : 'REJECTED');

        TitleReview::updateOrCreate(
            ['final_project_id' => $finalProject->id],
            array_merge($scores, [
                'reviewer_id' => $request->user()->id,
                'total_score' => $totalScore,
                'decision' => $decision,
                'comment' => $request->input('comment'),
            ])
        );

        $finalProject->update([
            'status' => $decision,
            'review_note' => $request->input('comment'),
            'approved_at' => $decision === 'APPROVED' ? now() : null,
        ]);

        return redirect()->route('admin.titles.index')->with('success', 'Review judul berhasil disimpan. Keputusan: '.$decision.'.');
    }
}
