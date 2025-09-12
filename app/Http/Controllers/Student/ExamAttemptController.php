<?php


namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExamAttemptController extends Controller
{
    /** Démarrer (ou reprendre) un examen */
    public function start(Exam $exam)
    {
        abort_unless($exam->is_published, 404);
        if ($exam->starts_at && now()->lt($exam->starts_at)) abort(403, 'Examen pas encore ouvert');
        if ($exam->ends_at && now()->gt($exam->ends_at))     abort(403, 'Examen terminé');

        $user = Auth::user();

        // une seule tentative par (exam, user) – contrainte unique déjà en DB
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->first();

        // Si la tentative existe ET a déjà été soumise → on empêche de recommencer
        if ($attempt && $attempt->submitted_at) {
            return redirect()->route('student.exams.thanks', $exam)
                ->with('status', 'Vous avez déjà soumis cet examen.');
        }

        // Sinon on (re)crée/démarre la tentative
        if (!$attempt) {
            $attempt = ExamAttempt::create([
                'exam_id'    => $exam->id,
                'user_id'    => $user->id,
                'started_at' => now(),
                // surtout pas null : évite l’erreur 1048
                'score'      => 0,
                'max_score'  => 0,
            ]);
        }

        // Clefs de session (anti double-submit + reprise)
        $sessionAttemptKey = "exam_{$exam->id}_attempt_id";
        $sessionTokenKey   = "exam_{$exam->id}_token";

        session([
            $sessionAttemptKey => $attempt->id,
            $sessionTokenKey   => Str::random(40),
        ]);

        $exam->load(['questions.choices']);

        return view('exams.start', [
            'exam'      => $exam,
            'attempt'   => $attempt,
            'examToken' => session($sessionTokenKey),
        ]);
    }

    /** Soumettre les réponses, calculer la note et enregistrer */
    public function submit(Request $request, Exam $exam)
    {
        $user = Auth::user();
        $sessionAttemptKey = "exam_{$exam->id}_attempt_id";
        $sessionTokenKey   = "exam_{$exam->id}_token";

        // tentative en session ?
        $attemptId = session($sessionAttemptKey);
        abort_unless($attemptId, 419, 'Session expirée.');

        /** @var ExamAttempt $attempt */
        $attempt = ExamAttempt::where('id', $attemptId)
            ->where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // anti double-submit
        $postedToken  = (string) $request->input('exam_token', '');
        $sessionToken = (string) session($sessionTokenKey);
        if (!$postedToken || $postedToken !== $sessionToken) {
            abort(419, 'Requête invalide ou déjà envoyée.');
        }
        session()->forget($sessionTokenKey);

        // déjà soumis ?
        if ($attempt->submitted_at) {
            return redirect()->route('student.exams.thanks', $exam);
        }

        // réponses envoyées
        $answersRadio = (array) $request->input('answers', []);        // single / true_false
        $answersMulti = (array) $request->input('answers_multi', []);  // multiple
        $answersText  = (array) $request->input('answers_text', []);   // text

        $exam->load(['questions.choices']);

        // Calcul et enregistrement dans une transaction
        DB::transaction(function () use ($attempt, $exam, $answersRadio, $answersMulti, $answersText) {

            // 1) on réécrit les réponses de la tentative (remplace si re-envoi)
            $attempt->answers()->delete();

            foreach ($exam->questions as $q) {
                if ($q->type === 'multiple') {
                    foreach ((array) ($answersMulti[$q->id] ?? []) as $choiceId) {
                        $attempt->answers()->create([
                            'question_id' => $q->id,
                            'choice_id'   => (int) $choiceId,
                            'value_text'  => null,
                        ]);
                    }
                } elseif ($q->type === 'single' || $q->type === 'true_false') {
                    $choiceId = $answersRadio[$q->id] ?? null;
                    $attempt->answers()->create([
                        'question_id' => $q->id,
                        'choice_id'   => $choiceId ? (int) $choiceId : null,
                        'value_text'  => null,
                    ]);
                } else { // text (non auto-noté)
                    $attempt->answers()->create([
                        'question_id' => $q->id,
                        'choice_id'   => null,
                        'value_text'  => $answersText[$q->id] ?? null,
                    ]);
                }
            }

            // 2) calcul de la note
            $autoTypes = ['single', 'true_false', 'multiple'];
            $gradable  = $exam->questions->whereIn('type', $autoTypes);
            $total     = $gradable->count();
            $correct   = 0;

            foreach ($gradable as $q) {
                // bonnes réponses (ensemble d’IDs)
                $good = $q->choices->where('is_correct', true)->pluck('id')->sort()->values();

                if ($q->type === 'multiple') {
                    // réponses choisies par l’étudiant
                    $given = collect((array) ($answersMulti[$q->id] ?? []))
                                ->map(fn($v) => (int) $v)
                                ->sort()->values();
                    if ($given->count() > 0 && $good->count() === $given->count()
                        && $good->diff($given)->isEmpty()) {
                        $correct++;
                    }
                } else { // single / true_false
                    $given = isset($answersRadio[$q->id]) ? (int) $answersRadio[$q->id] : null;
                    $correctId = $good->first(); // une seule bonne
                    if ($given && $correctId && $given === $correctId) {
                        $correct++;
                    }
                }
            }

            // % sur les questions auto-notées ; si aucune, 0
            $percent   = $total > 0 ? (int) round(($correct / $total) * 100, 0) : 0;

            // 3) finaliser la tentative
            $attempt->forceFill([
                'score'        => $percent,   // sur 100 (entier, conforme à ta colonne unsignedInteger)
                'max_score'    => 100,
                'submitted_at' => now(),
            ])->save();
        });
        // fenêtre de durée (hard stop informatif — on enregistre quand même)
        $duration = max(1, (int) $exam->duration_minutes);
        $maxEndAt = optional($attempt->started_at)->clone()->addMinutes($duration);
        if ($maxEndAt && now()->greaterThan($maxEndAt->addSeconds(20))) {
            // ici tu peux notifier le retard si tu veux
        }

        // nettoyage
        session()->forget($sessionAttemptKey);

        return redirect()->route('student.exams.thanks', $exam)
            ->with('status', 'Votre copie a été envoyée.');
    }

    public function thanks(Exam $exam)
    {
        return view('exams.thanks', compact('exam'));
    }

    /** Liste des examens disponibles */
    public function index(Request $request)
{
    $user = Auth::user();
    $now  = now();

    $exams = Exam::query()
        ->where('is_published', true)
        ->where(function ($q) use ($now) {
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
        })
        ->where(function ($q) use ($now) {
            $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
        })
        ->forLevel($user->level)          // 🔒 filtrage strict par niveau
        ->latest()
        ->paginate(12);

    return view('exams.index', compact('exams'));
}
}


