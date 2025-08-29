@extends('layouts.app')
@section('title', $level === 'L1' ? 'Licence 1 — Sujets' : 'Licence 2 — Sujets')

@section('content')

  <div class="max-w-7xl  p-2 mx-auto px-4 sm:px-6">
    <div class="py-6">
      <h1 class="text-3xl font-bold">{{ $level === 'L1' ? 'Licence 1' : 'Licence 2' }} — Sujets</h1>
      <p class="mt-1 text-slate-600">Choisissez un sujet pour accéder aux quiz & examens.</p>
      <div class="mt-4 inline-flex p-1 rounded-full border border-slate-200 bg-white shadow-sm">
        <a href="{{ route('subjects.l1') }}" class="px-3 py-1.5 rounded-full text-sm {{ $level==='L1' ? 'bg-emerald-600 text-white shadow' : 'hover:bg-slate-50' }}">Licence 1</a>
        <a href="{{ route('subjects.l2') }}" class="px-3 py-1.5 rounded-full text-sm {{ $level==='L2' ? 'bg-emerald-600 text-white shadow' : 'hover:bg-slate-50' }}">Licence 2</a>
      </div>

      <form method="GET" class="mt-4 flex items-center gap-2">
        <input type="text" name="q" value="{{ $q }}" placeholder="Rechercher un sujet…"
               class="w-full sm:w-96 rounded-full border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:ring-emerald-500">
        <button class="px-4 py-2 rounded-full bg-emerald-600 text-white text-sm hover:bg-emerald-500">Rechercher</button>
      </form>
    </div>
</div>

  {{-- Grille type “articles” : 1→2→3 colonnes --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 pb-10">
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @forelse($subjects as $subject)
        @include('subjects.partials.card', ['subject' => $subject])
      @empty
        <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600">
          Aucun sujet trouvé.
        </div>
      @endforelse
    </div>

    <div class="mt-8">
      {{ $subjects->onEachSide(1)->links() }}
    </div>
  </section>
@endsection
