<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitSeminarRevisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('MAHASISWA') ?? false;
    }

    public function rules(): array
    {
        return [
            'student_note' => ['required', 'string'],
            'revision_file' => ['required', 'file', 'max:5120', 'mimes:pdf,doc,docx,png,jpg,jpeg'],
        ];
    }
}
