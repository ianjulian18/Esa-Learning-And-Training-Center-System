<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'title', 'description', 'thumbnail', 'duration', 'passing_grade', 'status'];

    public function principals() { return $this->belongsToMany(Principal::class, 'course_principals'); }
    public function modules() { return $this->hasMany(Module::class)->orderBy('order'); }
    public function assignmentRules() { return $this->hasMany(AssignmentRule::class); }
    public function assessments() { return $this->hasMany(Assessment::class); }
    public function questions() { return $this->hasMany(Question::class); }
}

