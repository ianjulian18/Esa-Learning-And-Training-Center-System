<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = ['course_id', 'type', 'total_questions', 'passing_grade'];
    public function course() { return $this->belongsTo(Course::class); }
    public function questions() { return $this->hasMany(Question::class, 'course_id', 'course_id'); }
}

