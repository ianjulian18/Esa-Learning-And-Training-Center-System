<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AssessmentAnswer extends Model
{
    protected $fillable = ['assessment_attempt_id', 'question_id', 'user_answer', 'is_correct'];
    public function question() { return $this->belongsTo(Question::class); }
}
