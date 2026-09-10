<?php

namespace App\Models;

use App\Models\Room;
use App\Models\SeminarAssessment;
use App\Models\SeminarRevision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeminarSchedule extends Model
{
    protected $fillable = [
        'seminar_proposal_id',
        'date',
        'start_time',
        'end_time',
        'room_id',
        'status',
    ];

    public function seminarProposal(): BelongsTo
    {
        return $this->belongsTo(SeminarProposal::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function assessments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SeminarAssessment::class);
    }

    public function revisions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SeminarRevision::class);
    }
}
