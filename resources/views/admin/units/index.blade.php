@extends('layouts.admin-dark')
@section('title','Unités d’enseignement')
@section('content')
@include('admin.partials.flash')

<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Unités d’enseignement</h1>
  <a href="{{ route('admin.units.create') }}" class="px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">Nouvelle UE</a>
</div>

<form method="GET" class="mb-4 flex gap-3">
  <input type="text" name="q" value="{{ $q }}"
    placeholder="Rechercher une UE..."
    class="flex-1 rounded-lg border border-slate-300 px-3 py-2" />

  <select name="level" class="rounded-lg text-sm border border-slate-300 px-3 py-2">
    <option value="">Tous </option>
    <option value="L1" @selected($level==='L1' )>Licence 1</option>
    <option value="L2" @selected($level==='L2' )>Licence 2</option>
  </select>

  <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-500">
    Rechercher
  </button>
</form>


<div class="rounded-2xl border bg-white">
  <table class="w-full text-sm">
    <thead class="border-b bg-slate-50">
      <tr>
        <th class="p-3 text-left">Code</th>
        <th class="p-3 text-left">Intitulé</th>
         <th class="p-3 text-left">Niveau</th>
        <th class="p-3 text-right">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($units as $u)
      <tr class="border-b">
        <td class="p-3 font-mono">{{ $u->code }}</td>
        <td class="p-3">{{ $u->name }}</td>
        <td class="p-3">{{ $u->level }}</td>
        <td class="p-3 text-right space-x-2">
          <a class="px-2 py-1 rounded border hover:bg-slate-50" href="{{ route('admin.units.edit',$u) }}">Modifier</a>
          <form class="inline" action="{{ route('admin.units.destroy',$u) }}" method="POST" onsubmit="return confirm('Supprimer cette UE ?')">
            @csrf @method('DELETE')
            <button class="px-2 py-1 rounded bg-rose-600 text-white hover:bg-rose-500">Supprimer</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td class="p-3 text-slate-500" colspan="3">Aucune UE.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $units->links() }}</div>
@endsection