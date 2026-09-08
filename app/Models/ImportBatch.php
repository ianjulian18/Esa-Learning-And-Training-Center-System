<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'type', 'file_name', 'uploaded_by', 'total_rows', 'status'];

    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
    public function rows() { return $this->hasMany(ImportRow::class); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $year = date('Y');
                $lastBatch = self::where('id', 'like', "IMP-$year-%")->orderBy('id', 'desc')->first();
                $seq = $lastBatch ? intval(substr($lastBatch->id, -6)) + 1 : 1;
                $model->id = sprintf("IMP-%s-%06d", $year, $seq);
            }
        });
    }
}
