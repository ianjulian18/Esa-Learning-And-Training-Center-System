<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    protected $fillable = ['admin_id', 'status', 'total_rows', 'processed_rows', 'failed_rows'];
    public function rows() { return $this->hasMany(ImportRow::class); }
}
