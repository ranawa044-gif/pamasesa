<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SeminarProposal extends Model
{
    protected $fillable = [
        'final_project_id',
        'status',
        'proposal_file',
        'student_note',
        'supervisor_one_approval',
        'supervisor_two_approval',
        'supervisor_one_note',
        'supervisor_two_note',
        'supervisor_one_score',
        'supervisor_two_score',
        'final_note',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function finalProject(): BelongsTo
    {
        return $this->belongsTo(FinalProject::class);
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(SeminarSchedule::class);
    }

    public function readyForSeminar(): bool
    {
        return $this->supervisor_one_approval === 'APPROVED' && $this->supervisor_two_approval === 'APPROVED';
    }
}
