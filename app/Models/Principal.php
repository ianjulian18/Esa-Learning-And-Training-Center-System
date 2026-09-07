<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Principal extends Model
{
    protected $fillable = ['code', 'name'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_principals');
    }
}
