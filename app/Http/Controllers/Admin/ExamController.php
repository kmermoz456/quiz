<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index()
    {
        // Pas de 'subject' sur Exam dans ce modèle; on peut charger le count des questions
        $exams = Exam::withCount('questions')->latest()->paginate(15);

        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        // Charger les questions (avec sujet/UE pour l'affichage)
        $questions = Question::with('subject.unit')
            ->orderBy('subject_id')
            ->orderBy('position')
            ->get();

        return view('admin.exams.create', compact('questions'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'             => 'required|string|max:255',
            'duration_minutes'  => 'required|integer|min:1',
            'starts_at'         => 'nullable|date',
            'ends_at'           => 'nullable|date|after:starts_at',
            'is_published'      => 'sometimes|boolean',
            'question_ids'      => 'array',
            'question_ids.*'    => 'integer|exists:questions,id',
        ]);
        $data['is_published'] = (bool)($data['is_published'] ?? false);

        DB::transaction(function () use ($data) {
            $exam = Exam::create([
                'title'            => $data['title'],
                'duration_minutes' => $data['duration_minutes'],
                'starts_at'        => $data['starts_at'] ?? null,
                'ends_at'          => $data['ends_at'] ?? null,
                'is_published'     => $data['is_published'],
            ]);

            // Sync des questions (avec position auto si tu as la colonne)
            $payload = [];
            if (!empty($data['question_ids'])) {
                $pos = 1;
                foreach ($data['question_ids'] as $qid) {
                    $payload[$qid] = ['position' => $pos++]; // retire 'position' si absent dans le pivot
                }
            }
            $exam->questions()->sync($payload); // ou ->sync($data['question_ids'] ?? []) si pas de position
        });

        return redirect()->route('admin.exams.index')->with('ok', 'Examen créé');
    }

    public function edit(Exam $exam)
    {
        $questions = Question::with('subject.unit')
            ->orderBy('subject_id')
            ->orderBy('position')
            ->get();

        $exam->load('questions');

        return view('admin.exams.edit', compact('exam', 'questions'));
    }

    public function update(Request $r, Exam $exam)
    {
        $data = $r->validate([
            'title'             => 'required|string|max:255',
            'duration_minutes'  => 'required|integer|min:1',
            'starts_at'         => 'nullable|date',
            'ends_at'           => 'nullable|date|after:starts_at',
            'is_published'      => 'sometimes|boolean',
            'question_ids'      => 'array',
            'question_ids.*'    => 'integer|exists:questions,id',
        ]);
        $data['is_published'] = (bool)($data['is_published'] ?? false);

        DB::transaction(function () use ($exam, $data) {
            $exam->update([
                'title'            => $data['title'],
                'duration_minutes' => $data['duration_minutes'],
                'starts_at'        => $data['starts_at'] ?? null,
                'ends_at'          => $data['ends_at'] ?? null,
                'is_published'     => $data['is_published'],
            ]);

            $payload = [];
            if (!empty($data['question_ids'])) {
                $pos = 1;
                foreach ($data['question_ids'] as $qid) {
                    $payload[$qid] = ['position' => $pos++]; // retire si pas de position
                }
            }
            $exam->questions()->sync($payload); // ou ->sync($data['question_ids'] ?? [])
        });

        return back()->with('ok', 'Examen mis à jour');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return back()->with('ok', 'Examen supprimé');
    }

    public function export(Exam $exam, string $format)
    {
        // (Ton code d'export tel quel)
        abort(404);
    }
}
