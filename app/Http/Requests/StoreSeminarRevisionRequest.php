<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSeminarRevisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'revision_category' => ['required', Rule::in(['BAB_1', 'BAB_2', 'BAB_3', 'SYSTEM', 'OTHER'])],
            'revision_note' => ['required', 'string', 'max:2000'],
        ];
    }
}
