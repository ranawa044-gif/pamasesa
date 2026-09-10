<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
            'nim' => ['required', 'string', 'max:50', 'unique:students,nim'],
            'nama' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'digits:4'],
            'kelas' => ['required', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
