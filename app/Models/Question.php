<?php

// app/Models/Question.php
namespace App\Models;

use App\Models\Concerns\OwnedByUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use OwnedByUser;
    protected $fillable = ['subject_id','statement','type','points','position'];


    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'exam_question')
                    ->withPivot('position')
                    ->withTimestamps();
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class); // nom de la table = choices
    }
}
