<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuidanceComment extends Model
{
    protected $fillable = [
        'progress_log_id',
        'user_id',
        'comment',
    ];

    public function progressLog(): BelongsTo
    {
        return $this->belongsTo(ProgressLog::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
