<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SujetController extends Controller
{
    /**
     * SUJET — Entraînement : affiche les questions du sujet.
     * GET /subjects/{subject}
     */
    public function show(Subject $subject)
    {
        // Chargements utiles
        $subject->load('unit');

        // Les questions du sujet (+ options si QCM/QCU/VF)
       // show() : charger les choix
$questions = $subject->questions()
    ->with('choices')
    ->orderBy('position')
    ->get();


        // Pas encore de score (premier affichage)
        return view('subjects.practice', [
            'subject'   => $subject,
            'questions' => $questions,
            'score'     => null,
            'details'   => [],
        ]);
    }

    /**
     * SUJET — Soumission : corrige et renvoie le score immédiatement.
     * POST /subjects/{subject}/submit
     */
    public function submit(Request $request, Subject $subject)
    {
        $questions = $subject->questions()->with('options')->orderBy('position')->get();

        $answersRadio = $request->input('answers', []);          // single, true_false
        $answersMulti = $request->input('answers_multi', []);    // multiple => array d’IDs
        $answersText  = $request->input('answers_text', []);     // texte libre (non noté auto)

        $total   = max(1, $questions->count());
        $good    = 0;
        $details = [];

        foreach ($questions as $q) {
            $isCorrect  = false;
            $userAnswer = [];
            $type       = $q->type; // 'single' | 'multiple' | 'true_false' | 'text'

            if ($type === 'single') {
                $chosen    = $answersRadio[$q->id] ?? null;           // option_id
                $isCorrect = $q->options->where('id', $chosen)->where('is_correct', true)->isNotEmpty();
                $userAnswer = $chosen ? [(int)$chosen] : [];
            }
            elseif ($type === 'multiple') {
                $chosen  = collect($answersMulti[$q->id] ?? [])->map(fn($v)=>(int)$v)->values();
                $correct = $q->options->where('is_correct', true)->pluck('id')->values();
                $isCorrect = $chosen->count() > 0
                          && $chosen->diff($correct)->isEmpty()
                          && $correct->diff($chosen)->isEmpty();
                $userAnswer = $chosen->toArray();
            }
            elseif ($type === 'true_false') {
                // On suppose que les options contiennent une bonne réponse marquée is_correct
                $chosen     = $answersRadio[$q->id] ?? null;           // 'true' | 'false' ou option_id selon ton modèle
                $correctOpt = $q->options->firstWhere('is_correct', true);

                // Deux cas de modèles possibles :
                // - si tu stockes VF comme options {value:'true'/'false'}
                // - ou comme labels 'Vrai' / 'Faux'
                $correctValue = $correctOpt?->value ?? ($correctOpt && strcasecmp($correctOpt->label,'Vrai') === 0 ? 'true' : 'false');
                $isCorrect    = $correctOpt && (string)$chosen === (string)$correctValue;

                $userAnswer = [$chosen];
            }
            else { // 'text' : non noté automatiquement
                $userAnswer = [$answersText[$q->id] ?? null];
                $isCorrect  = false;
                $total--; // on ne compte pas la question texte dans le dénominateur
            }

            if ($isCorrect) $good++;

            $details[] = [
                'question'    => $q,
                'correct'     => $isCorrect,
                'userAnswer'  => $userAnswer,
                'correctOpts' => $q->options->where('is_correct', true)->pluck('id')->toArray(),
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

    /** Liste des sujets Licence 1 */
    public function indexL1(Request $request)
    {
        return $this->indexByLevel('L1', $request);
    }

    /** Liste des sujets Licence 2 */
    public function indexL2(Request $request)
    {
        return $this->indexByLevel('L2', $request);
    }

    /**
     * Implémentation commune : filtre par niveau + recherche + pagination.
     * Retourne la vue 'subjects.index' avec $level, $subjects, $q.
     */
 private function indexByLevel(string $level, Request $request)
{
    $q = trim((string) $request->query('q', ''));

    $subjects = Subject::query()
        ->with('unit')
        // nb d’examens distincts liés au sujet via ses questions
        ->withCount([
            'questions as exams_count' => function ($query) {
                $query->join('exam_question', 'exam_question.question_id', '=', 'questions.id')
                      ->select(DB::raw('count(distinct exam_question.exam_id)'));
            },
            // utile si tu veux aussi afficher le nombre de questions
            'questions',
        ])
        ->where('level', $level)
        ->when($q !== '', function ($query) use ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        })
        ->orderBy('title')
        ->paginate(12)
        ->withQueryString();

    return view('subjects.index', [
        'level'    => $level,
        'subjects' => $subjects,
        'q'        => $q,
    ]);
}
  

}
