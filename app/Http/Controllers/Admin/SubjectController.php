<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Unit;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    public function index()
    {
        $subjects = Subject::with('unit')->latest()->paginate(15);
        return view('admin.subjects.index', compact('subjects'));
    }
    public function create()
    {
        $units = Unit::orderBy('code')->get();
        return
            view('admin.subjects.create', compact('units'));
    }
    public function store(Request $r)
    {
        $data = $r->validate([
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|in:L1,L2',
        ]);

        Subject::create($data);
        return redirect()->route('admin.subjects.index')->with('ok', 'Sujet créé');
    }

    public function edit(Subject $subject)
    {
        $units = Unit::orderBy('code')->get();
        return view('admin.subjects.edit', compact('subject', 'units'));
    }
    public function update(Request $r, Subject $subject)
    {
        $data = $r->validate([
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|in:L1,L2',
        ]);
        $subject->update($data);
        return back()->with('ok', 'Sujet mis à jour');
    }

    public function destroy(Subject $subject){ $subject->delete(); return
back()->with('ok','Sujet supprimé'); }
}
