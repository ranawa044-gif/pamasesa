<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeminarRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'seminar_schedule_id',
        'lecturer_id',
        'revision_category',
        'revision_note',
        'student_note',
        'student_file_path',
        'validation_note',
        'status',
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
