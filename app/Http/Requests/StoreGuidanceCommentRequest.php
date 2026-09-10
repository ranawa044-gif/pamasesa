<?php

namespace App\Http\Requests;

use App\Models\ProgressLog;
use Illuminate\Foundation\Http\FormRequest;

class StoreGuidanceCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $progressLog = ProgressLog::with('finalProject.student', 'finalProject.supervisors')->find($this->input('progress_log_id'));

        if (! $user || ! $progressLog) {
            return false;
        }

        if ($user->isRole('MAHASISWA')) {
            return $progressLog->finalProject->student->user_id === $user->id;
        }

        if ($user->isRole('DOSEN')) {
            return $progressLog->finalProject->supervisors->contains('lecturer_id', $user->lecturer?->id);
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'progress_log_id' => ['required', 'exists:progress_logs,id'],
            'comment' => ['required', 'string'],
        ];
    }
}
