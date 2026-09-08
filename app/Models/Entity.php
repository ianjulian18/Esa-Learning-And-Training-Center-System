<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $fillable = ['name', 'code'];
    public function principals() { return $this->hasMany(Principal::class); }
}
