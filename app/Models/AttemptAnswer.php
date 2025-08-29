<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttemptAnswer extends Model
{
     protected $fillable = [
        'exam_attempt_id','question_id','choice_id','choice_ids','text_answer'
    ];

    protected $casts = [
        'choice_ids' => 'array',   // JSON -> array
    ];
public function attempt(){ return $this->belongsTo(ExamAttempt::class,'exam_attempt_id'); }
public function question(){ return $this->belongsTo(Question::class); }


public function submit(Request $request, Exam $exam)
{
    // ... vos vérifs & récupération $attempt déjà en place

   $answersRadio = (array) $request->input('answers', []);
$answersMulti = (array) $request->input('answers_multi', []);
$answersText  = (array) $request->input('answers_text', []);

$exam->load('questions');

foreach ($exam->questions as $q) {
    $choiceId   = null;
    $choiceIds  = [];
    $textAnswer = null;

    switch ($q->type) {
        case 'single':
        case 'true_false':
            $choiceId = isset($answersRadio[$q->id]) ? (int) $answersRadio[$q->id] : null;
            break;

        case 'multiple':
            $choiceIds = array_map('intval', $answersMulti[$q->id] ?? []);
            break;

        default: // text
            $textAnswer = $answersText[$q->id] ?? null;
            break;
    }

    AttemptAnswer::updateOrCreate(
        ['exam_attempt_id' => $attempt->id, 'question_id' => $q->id],
        ['choice_id' => $choiceId, 'choice_ids' => $choiceIds, 'text_answer' => $textAnswer]
    );
}

}


}
