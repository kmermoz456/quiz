<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAttempt extends Model
{
    protected $fillable = ['exam_id','user_id','started_at','submitted_at','score','max_score'];
protected $casts = ['started_at'=>'datetime','submitted_at'=>'datetime'];
public function exam(){ return $this->belongsTo(Exam::class); }
public function user(){ return $this->belongsTo(User::class); }
public function answers(){ return $this->hasMany(AttemptAnswer::class); }
 public function examAttempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
