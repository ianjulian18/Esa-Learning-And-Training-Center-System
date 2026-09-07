<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
        protected $guarded = [];
    protected $fillable = ['code', 'title', 'description', 'thumbnail', 'duration', 'passing_grade', 'status'];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    //
}


