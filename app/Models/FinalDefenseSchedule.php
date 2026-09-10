<?php

namespace App\Models;

use App\Models\FinalDefense;
use App\Models\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalDefenseSchedule extends Model
{
    protected $fillable = [
        'final_defense_id',
        'date',
        'start_time',
        'end_time',
        'room_id',
        'status',
    ];

    public function finalDefense(): BelongsTo
    {
        return $this->belongsTo(FinalDefense::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
