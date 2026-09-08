<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentHistory extends Model
{
    protected $fillable = [
        'user_id', 'nip', 'principal_id', 'position_id', 'department_id',
        'start_date', 'end_date', 'status', 'source'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function principal() { return $this->belongsTo(Principal::class); }
    public function position() { return $this->belongsTo(Position::class); }
    public function department() { return $this->belongsTo(Department::class); }
}

