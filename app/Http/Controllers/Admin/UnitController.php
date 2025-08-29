<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{

 public function index(Request $request)
{
    $q = $request->input('q');
    $level = $request->input('level');

    $units =Unit::query()
        ->when($q, fn($query) =>
            $query->where('name','like',"%{$q}%")
                  ->orWhere('code','like',"%{$q}%")
        )
        ->when($level, fn($query) =>
            $query->where('level',$level)
        )
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    return view('admin.units.index', compact('units','q','level'));
}

  public function create()
  {
    return view('admin.units.create');
  }
  public function store(Request $r)
  {
    $data = $r->validate(['code' => 'required|string|max:20|
unique:units,code', 'name' => 'required|string|max:255']);
    Unit::create($data);
    return redirect()->route('admin.units.index')->with('ok', 'UE créée');
  }
  public function edit(Unit $unit)
  {
    return view(
      'admin.units.edit',
      compact('unit')
    );
  }
  public function update(Request $r, Unit $unit)
  {
    $data = $r->validate(['code' => 'required|string|max:20|
unique:units,code,' . $unit->id, 'name' => 'required|string|max:255']);
    $unit->update($data);
    return back()->with('ok', 'UE mise à jour');
  }
  public function destroy(Unit $unit)
  {
    $unit->delete();
    return back()->with('ok', 'UE supprimée');
  }
}
