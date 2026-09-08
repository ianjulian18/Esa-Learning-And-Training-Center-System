<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentHistory extends Model
{
    protected $fillable = [
        'user_id', 'nip', 'entity_id', 'principal_id', 'region_id', 'area_id', 'position_id', 'department_id',
        'start_date', 'end_date', 'status', 'source'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function entity() { return $this->belongsTo(Entity::class); }
    public function principal() { return $this->belongsTo(Principal::class); }
    public function region() { return $this->belongsTo(Region::class); }
    public function area() { return $this->belongsTo(Area::class); }
    public function position() { return $this->belongsTo(Position::class); }
    public function department() { return $this->belongsTo(Department::class); }
}
