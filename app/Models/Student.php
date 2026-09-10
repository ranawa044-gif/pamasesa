<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'angkatan',
        'kelas',
        'phone',
        'guardian_phone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function finalProject(): HasOne
    {
        return $this->hasOne(FinalProject::class);
    }
}
