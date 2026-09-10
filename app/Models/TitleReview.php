<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_project_id',
        'reviewer_id',
        'problem_score',
        'solution_score',
        'complexity_score',
        'method_score',
        'testing_score',
        'total_score',
        'decision',
        'comment',
    ];

    public function finalProject()
    {
        return $this->belongsTo(FinalProject::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
