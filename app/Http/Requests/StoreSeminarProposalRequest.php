<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSeminarProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('MAHASISWA') ?? false;
    }

    public function rules(): array
    {
        return [
            'final_project_id' => ['required', 'exists:final_projects,id'],
            'proposal_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'student_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
