<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('ADMIN') ?? false;
    }

    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'duration_minutes' => ['required', 'integer', 'min:10'],
            'break_minutes' => ['required', 'integer', 'min:0'],
            'break_start' => ['nullable', 'date_format:H:i', 'required_with:break_end'],
            'break_end' => ['nullable', 'date_format:H:i', 'required_with:break_start', 'after:break_start'],
            'room_ids' => ['required', 'array'],
            'room_ids.*' => ['exists:rooms,id'],
        ];
    }
}
