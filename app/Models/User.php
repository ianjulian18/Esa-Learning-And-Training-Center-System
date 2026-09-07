<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employmentHistories()
    {
        return $this->hasMany(EmploymentHistory::class);
    }

    public function principalHistories()
    {
        return $this->hasMany(PrincipalHistory::class);
    }

    public function currentEmployment()
    {
        return $this->hasOne(EmploymentHistory::class)->where('status', 'ACTIVE')->latestOfMany();
    }
}
