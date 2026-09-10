<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewFinalProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('ADMIN') ?? false;
    }

    public function rules(): array
    {
        return [
            'problem_score' => ['required', 'integer', 'between:0,10'],
            'solution_score' => ['required', 'integer', 'between:0,10'],
            'complexity_score' => ['required', 'integer', 'between:0,10'],
            'method_score' => ['required', 'integer', 'between:0,10'],
            'testing_score' => ['required', 'integer', 'between:0,10'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
