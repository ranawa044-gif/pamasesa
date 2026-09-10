<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewSeminarRevisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('DOSEN') ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['DONE', 'REVISION'])],
            'validation_note' => ['nullable', 'string'],
        ];
    }
}
