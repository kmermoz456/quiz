@extends('layouts.admin-dark')
@section('title','Questions')
@section('content')
@include('admin.partials.flash')

<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Questions</h1>
  <a href="{{ route('admin.questions.create') }}" class="px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">Nouvelle question</a>
</div>

<div class="rounded-2xl border bg-white">
  <table class="w-full text-sm">
    <thead class="border-b bg-slate-50">
      <tr>
        <th class="p-3">Sujet</th><th class="p-3">Type</th><th class="p-3 text-left">Énoncé</th><th class="p-3 text-center">Pts</th><th class="p-3 text-right">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($questions as $q)
        <tr class="border-b">
          <td class="p-3">{{ $q->subject?->title }}</td>
          <td class="p-3 text-center font-mono">{{ $q->type }}</td>
          <td class="p-3">{{ Str::limit($q->statement,80) }}</td>
          <td class="p-3 text-center">{{ $q->points }}</td>
          <td class="p-3 text-right space-x-2">
            <a class="px-2 py-1 rounded border hover:bg-slate-50" href="{{ route('admin.questions.edit',$q) }}">Modifier</a>
            <form class="inline" action="{{ route('admin.questions.destroy',$q) }}" method="POST" onsubmit="return confirm('Supprimer cette question ?')">
              @csrf @method('DELETE')
              <button class="px-2 py-1 rounded bg-rose-600 text-white hover:bg-rose-500">Supprimer</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td class="p-3 text-slate-500" colspan="5">Aucune question.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $questions->links() }}</div>
@endsection
