<?php

namespace App\Models;

use App\Models\SeminarSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'capacity',
        'is_active',
    ];

    public function seminarSchedules(): HasMany
    {
        return $this->hasMany(SeminarSchedule::class);
    }
}
