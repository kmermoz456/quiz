{{-- resources/views/admin/results/index.blade.php --}}
@extends('layouts.admin-dark')
@section('title','Résultats')

@section('content')
<div class="space-y-6">
  {{-- Filtres --}}
  <form method="GET" class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
    <div class="grid md:grid-cols-5 gap-4">
      <div>
        <label class="text-xs text-slate-600">Du</label>
        <input type="date" name="start" value="{{ optional($start)->toDateString() }}"
          class="mt-1 w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
      </div>
      <div>
        <label class="text-xs text-slate-600">Au</label>
        <input type="date" name="end" value="{{ optional($end)->toDateString() }}"
          class="mt-1 w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
      </div>
      <div>
        <label class="text-xs text-slate-600">Niveau</label>
        <select name="level"
          class="mt-1 w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
          <option value="">Tous</option>
          <option value="L1" @selected($level==='L1')>L1</option>
          <option value="L2" @selected($level==='L2')>L2</option>
        </select>
      </div>
      <div class="md:col-span-2">
        <label class="text-xs text-slate-600">Recherche</label>
        <input type="text" name="q" value="{{ $q }}"
          placeholder="Nom, email, titre examen…"
          class="mt-1 w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
      </div>
    </div>

    <div class="mt-4 flex items-center gap-3">
      <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">Filtrer</button>

      {{-- Export CSV en conservant les filtres --}}
      <a href="{{ route('admin.results.export', request()->only('start','end','level','q')) }}"
         class="px-4 py-2 rounded-lg bg-sky-600 text-white hover:bg-sky-500">
        Export CSV
      </a>

      @if($count)
        <span class="text-slate-600 text-sm">
          Résultats :
          <strong class="text-slate-900">{{ $count }}</strong> • Moyenne :
          <strong class="text-slate-900">{{ $avgScore }}%</strong>
        </span>
      @endif
    </div>
  </form>

  {{-- Table résultats --}}
  <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-left text-slate-500 border-b border-slate-200">
          <tr>
            <th class="py-2">Date</th>
            <th>Étudiant</th>
            <th>Email</th>
            <th>Niveau</th>
            <th>Examen</th>
            <th>Score</th>
          </tr>
        </thead>
        <tbody class="text-slate-700">
          @forelse($results as $r)
            <tr class="border-b border-slate-100">
              <td class="py-2 text-slate-600">{{ $r->created_at->format('d/m/Y H:i') }}</td>
              <td>{{ $r->user->name ?? '—' }}</td>
              <td class="text-slate-500">{{ $r->user->email ?? '—' }}</td>
              <td class="text-slate-600">{{ $r->user->level ?? '—' }}</td>
              <td>{{ $r->exam->title ?? '—' }}</td>
              <td class="font-semibold {{ $r->score!==null ? 'text-emerald-600' : 'text-slate-400' }}">
                {{ $r->score }}%
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-6 text-slate-500 text-center">
                Aucun résultat avec ces filtres.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $results->links() }}</div>
  </div>
</div>
@endsection
