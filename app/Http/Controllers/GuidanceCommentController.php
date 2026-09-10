<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuidanceCommentRequest;
use App\Models\ProgressLog;
use Illuminate\Http\RedirectResponse;

class GuidanceCommentController extends Controller
{
    public function store(StoreGuidanceCommentRequest $request): RedirectResponse
    {
        $progressLog = ProgressLog::findOrFail($request->input('progress_log_id'));

        $progressLog->comments()->create([
            'user_id' => $request->user()->id,
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil disimpan.');
    }
}
