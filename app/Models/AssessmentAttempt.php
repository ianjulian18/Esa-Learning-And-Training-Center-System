<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    protected $fillable = ['assessment_id', 'user_id', 'score', 'status', 'started_at', 'completed_at'];
    protected $casts = ['started_at' => 'datetime', 'completed_at' => 'datetime'];
    public function answers() { return $this->hasMany(AssessmentAnswer::class); }
}
