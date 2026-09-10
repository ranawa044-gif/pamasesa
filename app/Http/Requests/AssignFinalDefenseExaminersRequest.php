<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Lecturer;

class AssignFinalDefenseExaminersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $lecturerIds = Lecturer::pluck('id')->toArray();

        return [
            'examiner_1_id' => ['required', Rule::in($lecturerIds)],
            'examiner_2_id' => ['required', Rule::in($lecturerIds)],
        ];
    }
}
