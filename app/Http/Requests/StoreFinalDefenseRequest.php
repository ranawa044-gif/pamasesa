<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinalDefenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'final_report_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'application_upload' => ['nullable', 'file', 'mimes:pdf,zip,rar,doc,docx,txt', 'max:20480'],
            'application_link' => ['nullable', 'string', 'url', 'max:2048'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->hasFile('application_upload') && ! $this->filled('application_link')) {
                $validator->errors()->add('application_link', 'Silakan unggah file aplikasi atau berikan tautan repository.');
            }
        });
    }
}
