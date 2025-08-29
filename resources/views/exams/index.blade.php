{{-- resources/views/exams/index.blade.php --}}
@extends('layouts.app')
@section('title','Examens disponibles')

@section('content')
<div class="p-5">
  <h1 class="text-2xl font-semibold mb-4">Examens disponibles</h1>
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($exams as $e)
      <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-xs text-slate-600">Durée : {{ $e->duration_minutes }} min</div>
        <h3 class="mt-1 text-lg font-semibold">{{ $e->title }}</h3>
        <p class="mt-2 text-sm text-slate-600">
          @if($e->starts_at) Début {{ $e->starts_at->format('d/m H:i') }} @endif
          @if($e->ends_at) • Fin {{ $e->ends_at->format('d/m H:i') }} @endif
        </p>
        <div class="mt-3">
          <a href="{{ route('student.exams.start',$e) }}"
             class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-sm text-white hover:bg-emerald-500">
            Démarrer
          </a>
        </div>
      </article>
    @empty
      <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600">
        Aucun examen publié.
      </div>
    @endforelse
  </div>

  <div class="mt-6">{{ $exams->links() }}</div>
</div>
@endsection
