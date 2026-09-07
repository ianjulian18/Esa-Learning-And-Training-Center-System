<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = ['user_id', 'course_id', 'principal_id', 'assignment_rule_id', 'status', 'score', 'completion_date'];

    protected $casts = [
        'completion_date' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function course() { return $this->belongsTo(Course::class); }
    public function principal() { return $this->belongsTo(Principal::class); }
}
