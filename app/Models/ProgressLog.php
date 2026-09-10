<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GuidanceComment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgressLog extends Model
{
    protected $fillable = [
        'final_project_id',
        'progress_type',
        'percentage',
        'description',
        'file_path',
        'status',
        'review_note',
        'revision_file_path',
    ];

    public function finalProject(): BelongsTo
    {
        return $this->belongsTo(FinalProject::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(GuidanceComment::class)->latest();
    }
}
