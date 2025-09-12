<?php

// app/Models/Exam.php
namespace App\Models;

use App\Models\Concerns\OwnedByUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{

    use OwnedByUser;
    protected $fillable = ['title','duration_minutes','starts_at','ends_at','is_published'];

    protected $casts = [
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
        'is_published' => 'boolean',
    ];

    public function attempts(): HasMany     { return $this->hasMany(ExamAttempt::class); }
    public function subject(): BelongsTo     { return $this->belongsTo(Subject::class); } // garde si tu as un sujet “principal” (optionnel)

    // ✅ Pivot many-to-many
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'exam_question')
                    ->withPivot('position')
                    ->withTimestamps()
                    ->orderBy('exam_question.position');
    }

    /**
 * Un examen est “pour ce niveau” si :
 *  - il ne contient AUCUNE question d’un autre niveau
 *  - et contient AU MOINS une question du niveau demandé
 */
public function scopeForLevel($query, string $level)
{
    return $query
        // aucune question d’un niveau différent
        ->whereDoesntHave('questions.subject', function ($qq) use ($level) {
            $qq->where('level', '!=', $level);
        })
        // au moins une question de ce niveau
        ->whereHas('questions.subject', function ($qq) use ($level) {
            $qq->where('level', $level);
        });
}
}
