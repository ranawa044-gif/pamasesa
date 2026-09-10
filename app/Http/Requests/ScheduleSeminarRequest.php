<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleSeminarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('ADMIN') ?? false;
    }

    public function rules(): array
    {
        return [
            'seminar_proposal_id' => ['required', 'exists:seminar_proposals,id'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room_id' => ['required', 'exists:rooms,id'],
        ];
    }
}
