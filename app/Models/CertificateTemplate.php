<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = ['name', 'background_image', 'layout_json'];
    protected $casts = ['layout_json' => 'array'];
}
