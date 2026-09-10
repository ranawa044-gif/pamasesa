<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProgressLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('MAHASISWA') ?? false;
    }

    public function rules(): array
    {
        return [
            'progress_type' => ['required', Rule::in(['PROPOSAL', 'DESIGN', 'IMPLEMENTATION', 'TESTING', 'REVISION_AFTER_SEMINAR', 'FINAL_REPORT'])],
            'percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'description' => ['required', 'string'],
            'file' => ['nullable', 'file', 'max:5120', 'mimes:pdf,doc,docx,png,jpg,jpeg'],
        ];
    }
}
