<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Principal extends Model
{
    protected $fillable = ['entity_id', 'code', 'name'];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_principals');
    }
}
