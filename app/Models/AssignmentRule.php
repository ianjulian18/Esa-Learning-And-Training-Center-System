<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentRule extends Model
{
    protected $fillable = ['principal_id', 'course_id', 'department_id', 'effective_date', 'expiry_date', 'status'];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function principal() { return $this->belongsTo(Principal::class); }
    public function course() { return $this->belongsTo(Course::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function positions() { return $this->belongsToMany(Position::class, 'assignment_rule_positions'); }
}
