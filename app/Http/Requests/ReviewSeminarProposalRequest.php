<?php

namespace App\Http\Requests;

use App\Models\SeminarProposal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewSeminarProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! $this->user()?->isRole('DOSEN')) {
            return false;
        }

        $proposal = $this->route('seminarProposal');
        if (! $proposal instanceof SeminarProposal) {
            return false;
        }

        $lecturerId = $this->user()->lecturer?->id;

        return $lecturerId && $proposal->finalProject->supervisors()->where('lecturer_id', $lecturerId)->exists();
    }

    public function rules(): array
    {
        return [
            'approval' => ['required', Rule::in(['APPROVED', 'REVISION'])],
            'note' => ['string', 'max:1000', 'required_if:approval,REVISION'],
        ];
    }
}
