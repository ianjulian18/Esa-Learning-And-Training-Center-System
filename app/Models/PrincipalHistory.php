<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrincipalHistory extends Model
{
    protected $fillable = ['user_id', 'principal_id', 'start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function principal() { return $this->belongsTo(Principal::class); }
}
