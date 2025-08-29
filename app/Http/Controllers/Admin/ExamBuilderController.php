<?php

// app/Http/Controllers/Admin/ExamBuilderController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamBuilderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    /** Affiche la page de sélection des questions */
    public function edit(Exam $exam, Request $request)
    {
        $q   = trim((string) $request->query('q',''));
        $lvl = $request->query('level');   // 'L1' | 'L2' ou null
        $sub = $request->query('subject'); // subject_id éventuel

        $questions = Question::with('subject')
            ->when($lvl, fn($qq)=>$qq->whereHas('subject', fn($s)=>$s->where('level',$lvl)))
            ->when($sub, fn($qq)=>$qq->where('subject_id',$sub))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where('statement','like',"%{$q}%");
            })
            ->orderBy('subject_id')
            ->orderBy('position')
            ->paginate(20)
            ->withQueryString();

        $exam->load('questions.subject');

        return view('admin.exams.builder', compact('exam','questions','q','lvl','sub'));
    }

    /** Sauvegarde la sélection + l’ordre */
    public function update(Exam $exam, Request $request)
    {
        $data = $request->validate([
            'question_ids'     => ['array'],        // ids cochés
            'question_ids.*'   => ['integer','exists:questions,id'],
            'positions'        => ['array'],        // positions saisies
            'positions.*'      => ['integer','min:0'],
        ]);

        $ids = collect($data['question_ids'] ?? []);

        // Construire le tableau à passer à sync(): [id => ['position' => X]]
        $syncPayload = [];
        foreach ($ids as $qid) {
            $pos = (int) ($data['positions'][$qid] ?? 0);
            $syncPayload[$qid] = ['position' => $pos];
        }

        $exam->questions()->sync($syncPayload);

        return redirect()
            ->route('admin.exams.builder.edit', $exam)
            ->with('status','Questions mises à jour.');
    }
}
