<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    /**
     * Affiche les questions d’un sujet (mode entraînement)
     * GET /subjects/{subject}
     */
    public function show(Subject $subject)
    {
        // Subject -> questions -> choices
          $questions = $subject->questions()
        ->with('choices')       // ou ->with('options') si ta relation s’appelle options
        ->orderBy('id')         // évite les soucis si 'position' n'existe pas
        ->get();

       return view('subjects.practice', [
        'subject'   => $subject,
        'questions' => $questions,
        'score'     => null,
        'details'   => [],
    ]);
    }

    /**
     * Corrige et affiche le score immédiatement
     * POST /subjects/{subject}/submit
     */
    public function submit(Request $request, Subject $subject)
    {
        $questions = $subject->questions()
            ->with('choices')
            ->orderBy('position')
            ->get();

        // Réponses envoyées par le formulaire
        $answersRadio = (array) $request->input('answers', []);         // single / true_false (choice_id)
        $answersMulti = (array) $request->input('answers_multi', []);   // multiple : [question_id => [choice_id, ...]]
        $answersText  = (array) $request->input('answers_text', []);    // texte libre (non noté ici)

        $total   = max(1, $questions->count()); // sera décrémenté pour les questions "text"
        $good    = 0;
        $details = [];

        foreach ($questions as $q) {
            $isCorrect  = false;
            $userAnswer = [];
            $type       = $q->type; // 'single' | 'multiple' | 'true_false' | 'text'

            if ($type === 'single' || $type === 'true_false') {
                $chosen    = $answersRadio[$q->id] ?? null; // id d’un choice
                $isCorrect = $q->choices
                    ->where('id', (int) $chosen)
                    ->where('is_correct', true)
                    ->isNotEmpty();
                $userAnswer = $chosen ? [(int)$chosen] : [];
            }
            elseif ($type === 'multiple') {
                $chosen  = collect($answersMulti[$q->id] ?? [])
                            ->map(fn($v) => (int) $v)
                            ->values();
                $correct = $q->choices->where('is_correct', true)->pluck('id')->values();
                // exact match des ensembles
                $isCorrect = $chosen->count() > 0
                          && $chosen->diff($correct)->isEmpty()
                          && $correct->diff($chosen)->isEmpty();
                $userAnswer = $chosen->toArray();
            }
            else { // 'text' : non noté automatiquement
                $userAnswer = [$answersText[$q->id] ?? null];
                $isCorrect  = false;
                $total--; // on ne compte pas les questions texte dans le score
            }

            if ($isCorrect) {
                $good++;
            }

            $details[] = [
                'question'    => $q,
                'correct'     => $isCorrect,
                'userAnswer'  => $userAnswer,
                'correctOpts' => $q->choices->where('is_correct', true)->pluck('id')->toArray(),
            ];
        }

        $score = round(($good / max(1, $total)) * 100);

        return view('subjects.practice', [
            'subject'   => $subject,
            'questions' => $questions,
            'score'     => $score,
            'details'   => $details,
        ]);
    }
}
