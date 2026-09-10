<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignSupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('ADMIN') ?? false;
    }

    public function rules(): array
    {
        return [
            'pembimbing_1_id' => ['required', 'exists:lecturers,id', 'different:pembimbing_2_id'],
            'pembimbing_2_id' => ['required', 'exists:lecturers,id', 'different:pembimbing_1_id'],
        ];
    }
}
