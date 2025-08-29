<?php

namespace App\Models;

use App\Models\Concerns\OwnedByUser;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use OwnedByUser;

    protected $fillable = ['unit_id','title','description','level'];
public function unit(){ return $this->belongsTo(Unit::class); }

public function questions()
    {
        return $this->hasMany(Question::class);
    }
public function exams(){ return $this->hasMany(Exam::class); }
}
