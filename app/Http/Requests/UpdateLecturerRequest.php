<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLecturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('ADMIN') ?? false;
    }

    public function rules(): array
    {
        $lecturer = $this->route('lecturer');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($lecturer->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'nidn' => ['required', 'string', 'max:50', Rule::unique('lecturers', 'nidn')->ignore($lecturer->id)],
            'nama' => ['required', 'string', 'max:255'],
            'bidang_keahlian' => ['nullable', 'string', 'max:255'],
        ];
    }
}
