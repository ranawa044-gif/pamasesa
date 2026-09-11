<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewProgressLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('DOSEN') ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(['pending', 'verified', 'PENDING', 'VERIFIED'])],
            'review_note' => ['nullable', 'string'],
            'review_file' => ['nullable', 'file', 'max:5120', 'mimes:pdf,doc,docx,png,jpg,jpeg'],
        ];
    }
}
