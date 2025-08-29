@extends('layouts.admin-dark')
@section('title','Examens')
@section('content')
@include('admin.partials.flash')

<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Examens</h1>
  <a href="{{ route('admin.exams.create') }}" class="px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">Nouvel examen</a>
</div>

<div class="rounded-2xl border bg-white">
  <table class="w-full text-sm">
    <thead class="border-b bg-slate-50">
      <tr>
        <th class="p-3 text-left">Titre</th><th class="p-3 text-center">Durée</th>
        <th class="p-3 text-center">Publié</th><th class="p-3 text-right">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($exams as $e)
        <tr class="border-b">
          <td class="p-3">{{ $e->title }}</td>
          <td class="p-3 text-center">{{ $e->duration_minutes }} min</td>
          <td class="p-3 text-center">
            <span class="px-2 py-0.5 rounded text-xs {{ $e->is_published ? 'bg-emerald-100 text-emerald-700':'bg-slate-100 text-slate-600' }}">
              {{ $e->is_published ? 'Oui':'Non' }}
            </span>
          </td>
          <td class="p-3 text-right space-x-2">
            <a class="px-2 py-1 rounded border hover:bg-slate-50" href="{{ route('admin.exams.edit',$e) }}">Modifier</a>
            <a class="px-2 py-1 rounded border hover:bg-slate-50" href="{{ route('admin.exams.builder.edit',$e) }}">Builder</a>
            <form class="inline" action="{{ route('admin.exams.destroy',$e) }}" method="POST" onsubmit="return confirm('Supprimer cet examen ?')">
              @csrf @method('DELETE')
              <button class="px-2 py-1 rounded bg-rose-600 text-white hover:bg-rose-500">Supprimer</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td class="p-3 text-slate-500" colspan="4">Aucun examen.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $exams->links() }}</div>



@endsection
