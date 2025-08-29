@extends('layouts.admin-dark')
@section('title','Sujets')
@section('content')
@include('admin.partials.flash')

<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Sujets</h1>
  <a href="{{ route('admin.subjects.create') }}" class="px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">Nouveau sujet</a>
</div>

<div class="rounded-2xl border bg-white">
  <table class="w-full text-sm">
    <thead class="border-b bg-slate-50">
      <tr>
        <th class="p-3 text-left">UE</th><th class="p-3 text-left">Sujet</th><th class="p-3">Niveau</th><th class="p-3 text-right">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($subjects as $s)
        <tr class="border-b">
          <td class="p-3">{{ $s->unit?->code }}</td>
          <td class="p-3">{{ $s->title }}</td>
          <td class="p-3 text-center">{{ $s->level }}</td>
          <td class="p-3 text-right space-x-2">
            <a class="px-2 py-1 rounded border hover:bg-slate-50" href="{{ route('admin.subjects.edit',$s) }}">Modifier</a>
            <form class="inline" action="{{ route('admin.subjects.destroy',$s) }}" method="POST" onsubmit="return confirm('Supprimer ce sujet ?')">
              @csrf @method('DELETE')
              <button class="px-2 py-1 rounded bg-rose-600 text-white hover:bg-rose-500">Supprimer</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td class="p-3 text-slate-500" colspan="4">Aucun sujet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $subjects->links() }}</div>
@endsection
