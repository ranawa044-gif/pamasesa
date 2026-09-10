<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\SeminarProposal;
use App\Models\TitleReview;

class FinalProject extends Model
{
    protected $fillable = [
        'student_id',
        'title',
        'background',
        'research_object',
        'business_process',
        'proposed_supervisor_name',
        'system_type',
        'main_features',
        'actor_count',
        'development_method',
        'additional_method',
        'technology',
        'testing_plan',
        'declaration',
        'status',
        'review_note',
        'submitted_at',
        'approved_at',
        'supervisor_one_approval',
        'supervisor_two_approval',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'declaration' => 'boolean',
            'supervisor_one_approval' => 'string',
            'supervisor_two_approval' => 'string',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisors(): HasMany
    {
        return $this->hasMany(Supervisor::class);
    }

    public function progressLogs(): HasMany
    {
        return $this->hasMany(ProgressLog::class)->latest();
    }

    public function seminarProposal(): HasOne
    {
        return $this->hasOne(SeminarProposal::class);
    }

    public function titleReview(): HasOne
    {
        return $this->hasOne(TitleReview::class);
    }

    public function finalDefense(): HasOne
    {
        return $this->hasOne(FinalDefense::class)->latestOfMany();
    }

    public function finalDefenses(): HasMany
    {
        return $this->hasMany(FinalDefense::class);
    }

    public function examiners(): HasManyThrough
    {
        return $this->hasManyThrough(Examiner::class, FinalDefense::class, 'final_project_id', 'final_defense_id', 'id', 'id');
    }

    public function latestProgress(): ?ProgressLog
    {
        return $this->progressLogs->first();
    }

    public function readyForDefense(): bool
    {
        return $this->status === 'READY_FOR_DEFENSE' || ($this->supervisor_one_approval === 'APPROVED' && $this->supervisor_two_approval === 'APPROVED');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'APPROVED');
    }

    public function supervisorOne(): ?Supervisor
    {
        return $this->supervisors->firstWhere('type', 'PEMBIMBING_1');
    }

    public function supervisorTwo(): ?Supervisor
    {
        return $this->supervisors->firstWhere('type', 'PEMBIMBING_2');
    }
}
