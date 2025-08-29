{{-- resources/views/subjects/show.blade.php --}}
@extends('layouts.app')
@section('title', $subject->title . ' — ' . (isset($exam) ? $exam->title : 'Examens'))

@section('content')
<div class="p-5">

  {{-- En-tête sujet --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5">
    <div class="flex items-start justify-between gap-4">
      <div>
        <div class="text-xs text-slate-600 flex items-center gap-2">
          <span class="inline-flex rounded px-2 py-0.5 border border-slate-200 bg-slate-50">
            UE {{ $subject->unit->code ?? '—' }}
          </span>
          <span class="inline-flex rounded px-2 py-0.5 border border-slate-200 bg-slate-50">
            {{ $subject->level }}
          </span>
        </div>
        <h1 class="mt-1 text-2xl font-semibold text-slate-900">{{ $subject->title }}</h1>
        @unless(isset($exam))
          <p class="mt-2 text-sm text-slate-600 max-w-3xl">{{ $subject->description }}</p>
        @endunless
      </div>
      <a href="{{ url()->previous() }}" class="text-sm text-emerald-600 hover:text-emerald-500">← Retour</a>
    </div>
  </div>

  {{-- ====== CAS 1 : FORMULAIRE DE QUESTIONNAIRE ====== --}}
  @isset($exam)
    <section class="mt-6">
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
          <div>
            <div class="text-xs text-slate-600">Durée : {{ $exam->duration_minutes }} min</div>
            <h2 class="mt-1 text-xl font-semibold text-slate-900">{{ $exam->title }}</h2>
            @if($exam->starts_at || $exam->ends_at)
              <p class="text-sm text-slate-600">
                @if($exam->starts_at) Début {{ $exam->starts_at->format('d/m H:i') }}@endif
                @if($exam->ends_at) • Fin {{ $exam->ends_at->format('d/m H:i') }}@endif
              </p>
            @endif
          </div>
          <div class="text-sm text-slate-600">
            {{ $questions->count() }} question(s)
          </div>
        </div>

        @auth
          <form method="POST" action="{{ route('student.exams.submit', $exam) }}" class="mt-4 space-y-5">
            @csrf

            @foreach($questions as $i => $q)
              <article class="rounded-xl border border-slate-200 p-4">
                <div class="flex items-start gap-3">
                  <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white text-sm">
                    {{ $i+1 }}
                  </span>
                  <div class="w-full">
                    <h3 class="font-medium text-slate-900">
                      {!! nl2br(e($q->statement)) !!}
                    </h3>

                    {{-- Type: single (QCU) --}}
                    @if($q->type === 'single')
                      <div class="mt-3 space-y-2">
                        @foreach($q->options as $opt)
                          <label class="flex items-center gap-2">
                            <input type="radio"
                                   name="answers[{{ $q->id }}]"
                                   value="{{ $opt->id }}"
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-slate-800">{{ $opt->label }}</span>
                          </label>
                        @endforeach
                      </div>

                    {{-- Type: multiple (QCM) --}}
                    @elseif($q->type === 'multiple')
                      <div class="mt-3 space-y-2">
                        @foreach($q->options as $opt)
                          <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="answers[{{ $q->id }}][]"
                                   value="{{ $opt->id }}"
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-slate-800">{{ $opt->label }}</span>
                          </label>
                        @endforeach
                      </div>

                    {{-- Type: vrai/faux --}}
                    @elseif($q->type === 'true_false')
                      <div class="mt-3 space-y-2">
                        <label class="flex items-center gap-2">
                          <input type="radio" name="answers[{{ $q->id }}]" value="true"
                                 class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                          <span class="text-sm text-slate-800">Vrai</span>
                        </label>
                        <label class="flex items-center gap-2">
                          <input type="radio" name="answers[{{ $q->id }}]" value="false"
                                 class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                          <span class="text-sm text-slate-800">Faux</span>
                        </label>
                      </div>

                    {{-- Type: texte libre / courte réponse --}}
                    @else
                      <div class="mt-3">
                        <textarea name="answers_text[{{ $q->id }}]"
                                  rows="3"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:ring-emerald-500"
                                  placeholder="Votre réponse…">{{ old("answers_text.$q->id") }}</textarea>
                      </div>
                    @endif
                  </div>
                </div>
              </article>
            @endforeach

            {{-- Boutons --}}
            <div class="flex items-center justify-between">
              <a href="{{ url()->previous() }}" class="text-sm text-slate-600 hover:text-slate-800">
                ← Retour
              </a>
              <button type="submit"
                      class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-white font-medium hover:bg-emerald-500">
                Soumettre mes réponses
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
              </button>
            </div>
          </form>
        @else
          <div class="mt-4">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm hover:bg-slate-50">
              Se connecter pour composer
            </a>
          </div>
        @endauth
      </div>
    </section>
  @endisset

  {{-- ====== CAS 2 : LISTE DES EXAMENS (si pas d’exam sélectionné) ====== --}}
  @empty($exam)
    <section class="mt-6">
      <h2 class="text-lg font-medium text-slate-900 mb-3">Examens</h2>
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($exams as $e)
          <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-xs text-slate-600">Durée : {{ $e->duration_minutes }} min</div>
            <h3 class="mt-1 text-lg font-semibold">{{ $e->title }}</h3>
            <p class="mt-2 text-sm text-slate-600">
              @if($e->starts_at) Début {{ $e->starts_at->format('d/m H:i') }} @endif
              @if($e->ends_at) • Fin {{ $e->ends_at->format('d/m H:i') }} @endif
            </p>
            <div class="mt-3">
              @auth
                {{-- lien pour charger le formulaire sur cette même vue --}}
                <a href="{{ route('subjects.show', [$subject, 'exam' => $e->id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-sm text-white hover:bg-emerald-500">
                  Composer
                </a>
              @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm hover:bg-slate-50">
                  Se connecter pour commencer
                </a>
              @endauth
            </div>
          </article>
        @empty
          <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-600">
            Aucun examen publié pour ce sujet.
          </div>
        @endforelse
      </div>
    </section>
  @endempty

</div>
@endsection
