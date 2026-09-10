<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeminarAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'seminar_schedule_id',
        'lecturer_id',
        'problem_score',
        'method_score',
        'design_score',
        'implementation_score',
        'presentation_score',
        'total_score',
        'decision',
        'revision_note',
        'assessed_at',
    ];

    protected $casts = [
        'assessed_at' => 'datetime',
    ];

    public function seminarSchedule(): BelongsTo
    {
        return $this->belongsTo(SeminarSchedule::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}
