<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FinalDefenseSchedule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FinalDefense extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_project_id',
        'final_report_file',
        'application_file',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function finalProject(): BelongsTo
    {
        return $this->belongsTo(FinalProject::class);
    }

    public function examiners(): HasMany
    {
        return $this->hasMany(Examiner::class);
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(FinalDefenseSchedule::class);
    }
}
