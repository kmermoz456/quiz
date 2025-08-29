<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Question, Subject, Choice};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class QuestionController extends Controller
{

    

public function store(Request $r)
{
    $data = $r->validate([
        'subject_id'   => 'required|exists:subjects,id',
        'statement'    => 'required|string',
        'type'         => 'required|in:single,multiple,true_false,text',
        'points'       => 'required|integer|min:1',
        'position'     => 'nullable|integer|min:0',
        'choices_json' => 'nullable|string', // <-- les choix arrivent en JSON
    ]);

    // normaliser les choix
    $choices = collect(json_decode($data['choices_json'] ?? '[]', true))
        ->map(fn($c) => [
            'label'      => trim($c['label'] ?? ''),
            'is_correct' => !empty($c['is_correct']),
        ])
        ->filter(fn($c) => $c['label'] !== '')
        ->values();

    DB::transaction(function () use ($data, $choices) {
        $q = Question::create([
            'subject_id' => $data['subject_id'],
            'statement'  => $data['statement'],
            'type'       => $data['type'],
            'points'     => $data['points'],
            'position'   => $data['position'] ?? 1,
        ]);

        // Pas de choix pour les questions "text"
        if ($q->type === 'text') {
            return;
        }

        // true_false sans choix -> auto
        if ($q->type === 'true_false' && $choices->isEmpty()) {
            Choice::insert([
                ['question_id' => $q->id, 'label' => 'Vrai', 'is_correct' => true],
                ['question_id' => $q->id, 'label' => 'Faux', 'is_correct' => false],
            ]);
            return;
        }

        foreach ($choices as $c) {
            $q->choices()->create($c);
        }
    });

    return redirect()->route('admin.questions.index')->with('ok', 'Question créée');
}

public function update(Request $r, Question $question)
{
    $data = $r->validate([
        'subject_id'   => 'required|exists:subjects,id',
        'statement'    => 'required|string',
        'type'         => 'required|in:single,multiple,true_false,text',
        'points'       => 'required|integer|min:1',
        'position'     => 'nullable|integer|min:0',
        'choices_json' => 'nullable|string',
    ]);

    $choices = collect(json_decode($data['choices_json'] ?? '[]', true))
        ->map(fn($c) => [
            'id'         => $c['id'] ?? null,
            'label'      => trim($c['label'] ?? ''),
            'is_correct' => !empty($c['is_correct']),
        ])
        ->filter(fn($c) => $c['label'] !== '')
        ->values();

    DB::transaction(function () use ($question, $data, $choices) {
        $question->update([
            'subject_id' => $data['subject_id'],
            'statement'  => $data['statement'],
            'type'       => $data['type'],
            'points'     => $data['points'],
            'position'   => $data['position'] ?? 1,
        ]);

        // Gestion des choix
        if ($question->type === 'text') {
            $question->choices()->delete();
            return;
        }

        if ($question->type === 'true_false' && $choices->isEmpty()) {
            $question->choices()->delete();
            Choice::insert([
                ['question_id' => $question->id, 'label' => 'Vrai', 'is_correct' => true],
                ['question_id' => $question->id, 'label' => 'Faux', 'is_correct' => false],
            ]);
            return;
        }

        // Sync (create/update/delete)
        $existingIds = $question->choices()->pluck('id');
        $keptIds     = collect();

        foreach ($choices as $c) {
            if (!empty($c['id']) && $existingIds->contains($c['id'])) {
                $question->choices()->whereKey($c['id'])->update([
                    'label'      => $c['label'],
                    'is_correct' => $c['is_correct'],
                ]);
                $keptIds->push($c['id']);
            } else {
                $new = $question->choices()->create([
                    'label'      => $c['label'],
                    'is_correct' => $c['is_correct'],
                ]);
                $keptIds->push($new->id);
            }
        }

        $toDelete = $existingIds->diff($keptIds);
        if ($toDelete->isNotEmpty()) {
            $question->choices()->whereIn('id', $toDelete)->delete();
        }
    });

    return back()->with('ok', 'Question mise à jour');
}

    public function index()
    {
        $questions = Question::with('subject.unit')->orderByDesc('id')->paginate(20);
        return view(
            'admin.questions.index',
            compact('questions')
        );
    }
    public function create()
    {
        $subjects = Subject::with('unit')->orderBy('title')->get();
        return view(
            'admin.questions.create',
            compact('subjects')
        );
    }
  
    public function edit(Question $question)
    {

        $subjects =
            Subject::orderBy('title')->get();
        $question->load('choices');
        return
            view('admin.questions.edit', compact('question', 'subjects'));
    }

   

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('ok', 'Question supprimée');
    }
}
