<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitFinalProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('MAHASISWA') ?? false;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:30'],
            'guardian_phone' => ['required', 'string', 'max:30'],
            'title' => ['required', 'string', 'max:255'],
            'background' => ['required', 'string'],
            'research_object' => ['required', 'string'],
            'business_process' => ['required', 'string'],
            'proposed_supervisor_name' => ['required', 'string', 'max:255'],
            'system_type' => ['required', 'string', 'max:100'],
            'main_features' => ['required', 'string'],
            'actor_count' => ['required', 'integer', 'min:2', 'max:50'],
            'development_method' => ['required', 'string', 'max:100'],
            'development_method_other' => ['required_if:development_method,Lainnya', 'nullable', 'string', 'max:100'],
            'additional_method' => ['nullable', 'array'],
            'additional_method.*' => ['string', 'max:100'],
            'additional_method_other' => ['nullable', 'string', 'max:100'],
            'technology' => ['required', 'string', 'max:255'],
            'testing_plan' => ['required_without:testing_plan_other', 'nullable', 'array', 'min:1'],
            'testing_plan.*' => ['string', 'max:100'],
            'testing_plan_other' => ['required_without:testing_plan', 'nullable', 'string', 'max:100'],
            'declaration' => ['accepted'],
        ];
    }
}
