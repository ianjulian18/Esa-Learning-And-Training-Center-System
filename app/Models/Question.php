<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];
    protected $casts = ['options' => 'array'];
    public function bank() { return $this->belongsTo(QuestionBank::class, 'question_bank_id'); }
}

