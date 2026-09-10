<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSeminarAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'problem_score' => ['required', 'integer', 'between:1,5'],
            'method_score' => ['required', 'integer', 'between:1,5'],
            'design_score' => ['required', 'integer', 'between:1,5'],
            'implementation_score' => ['required', 'integer', 'between:1,5'],
            'presentation_score' => ['required', 'integer', 'between:1,5'],
            'decision' => ['required', Rule::in(['PASSED', 'PASSED_WITH_REVISION', 'REPEAT'])],
            'revision_note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
