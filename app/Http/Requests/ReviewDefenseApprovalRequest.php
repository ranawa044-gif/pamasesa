<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewDefenseApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('DOSEN') ?? false;
    }

    public function rules(): array
    {
        return [
            'approval_status' => ['required', Rule::in(['APPROVED', 'REVISION'])],
        ];
    }
}
