<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLecturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isRole('ADMIN') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'nidn' => ['required', 'string', 'max:50', 'unique:lecturers,nidn'],
            'nama' => ['required', 'string', 'max:255'],
            'bidang_keahlian' => ['nullable', 'string', 'max:255'],
        ];
    }
}
