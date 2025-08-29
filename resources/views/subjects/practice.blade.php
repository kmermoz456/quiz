@extends('layouts.app')
@section('title', $subject->title.' — Entraînement')

@section('content')
<div class="p-5">
  {{-- Header sujet --}}
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
        <p class="mt-2 text-sm text-slate-600 max-w-3xl">{{ $subject->description }}</p>
      </div>
      <a href="{{ url()->previous() }}" class="text-sm text-emerald-600 hover:text-emerald-500">← Retour</a>
    </div>
  </div>

  {{-- Score immédiat --}}
  @if(!is_null($score))
    <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3">
      <strong>Score :</strong> {{ $score }}%
    </div>
  @endif

  {{-- Formulaire des questions --}}
  <form method="POST" action="{{ route('subjects.practice.submit', $subject) }}" class="mt-6 space-y-5">
    @csrf

    @foreach($questions as $i => $q)
      @php
        $detail      = collect($details ?? [])->firstWhere('question.id', $q->id);
        $selected    = collect($detail['userAnswer'] ?? []);           // ids choisis par l’étudiant
        $correctOpts = collect($detail['correctOpts'] ?? []);          // ids des bonnes réponses
        $showResult  = !is_null($score) && $q->type !== 'text';
        $disableWhenScored = $showResult ? 'disabled' : '';
      @endphp

      <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-start gap-3">
          <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white text-sm">
            {{ $i+1 }}
          </span>
          <div class="w-full">
            <h3 class="font-medium text-slate-900">{!! nl2br(e($q->statement)) !!}</h3>

            {{-- SINGLE (QCU) --}}
            @if($q->type === 'single')
              <div class="mt-3 space-y-2">
                @foreach($q->choices as $ch)
                  @php
                    $isCorrectChoice = $correctOpts->contains($ch->id);
                    $isUserSelected  = $selected->contains($ch->id) || old("answers.$q->id") == $ch->id;
                    $rowClasses = $showResult
                      ? ($isCorrectChoice ? 'border-emerald-300 bg-emerald-50'
                        : ($isUserSelected ? 'border-rose-300 bg-rose-50' : ''))
                      : '';
                  @endphp
                  <label class="flex items-center gap-2 rounded-lg border px-3 py-2 {{ $rowClasses }}">
                    <input type="radio"
                           name="answers[{{ $q->id }}]"
                           value="{{ $ch->id }}"
                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500"
                           {{ $disableWhenScored }}
                           @checked( old("answers.$q->id") == $ch->id || $selected->contains($ch->id) )>
                    <span class="text-sm text-slate-800">{{ $ch->label }}</span>

                    @if($showResult && $isCorrectChoice)
                      <span class="ml-auto text-xs text-emerald-700 font-medium">Bonne réponse ✓</span>
                    @endif
                  </label>
                @endforeach
              </div>

            {{-- MULTIPLE (QCM) --}}
            @elseif($q->type === 'multiple')
              <div class="mt-3 space-y-2">
                @foreach($q->choices as $ch)
                  @php
                    $isCorrectChoice = $correctOpts->contains($ch->id);
                    $isUserSelected  = collect(old("answers_multi.$q->id"))->contains($ch->id) || $selected->contains($ch->id);
                    $rowClasses = $showResult
                      ? ($isCorrectChoice ? 'border-emerald-300 bg-emerald-50'
                        : ($isUserSelected ? 'border-rose-300 bg-rose-50' : ''))
                      : '';
                  @endphp
                  <label class="flex items-center gap-2 rounded-lg border px-3 py-2 {{ $rowClasses }}">
                    <input type="checkbox"
                           name="answers_multi[{{ $q->id }}][]"
                           value="{{ $ch->id }}"
                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500"
                           {{ $disableWhenScored }}
                           @checked( collect(old("answers_multi.$q->id"))->contains($ch->id) || $selected->contains($ch->id) )>
                    <span class="text-sm text-slate-800">{{ $ch->label }}</span>

                    @if($showResult && $isCorrectChoice)
                      <span class="ml-auto text-xs text-emerald-700 font-medium">Bonne réponse ✓</span>
                    @endif
                  </label>
                @endforeach
              </div>

            {{-- VRAI/FAUX (deux choices) --}}
            @elseif($q->type === 'true_false')
              <div class="mt-3 space-y-2">
                @foreach($q->choices as $ch)
                  @php
                    $isCorrectChoice = $correctOpts->contains($ch->id);
                    $isUserSelected  = $selected->contains($ch->id) || old("answers.$q->id") == $ch->id;
                    $rowClasses = $showResult
                      ? ($isCorrectChoice ? 'border-emerald-300 bg-emerald-50'
                        : ($isUserSelected ? 'border-rose-300 bg-rose-50' : ''))
                      : '';
                  @endphp
                  <label class="flex items-center gap-2 rounded-lg border px-3 py-2 {{ $rowClasses }}">
                    <input type="radio"
                           name="answers[{{ $q->id }}]"
                           value="{{ $ch->id }}"
                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500"
                           {{ $disableWhenScored }}
                           @checked( old("answers.$q->id") == $ch->id || $selected->contains($ch->id) )>
                    <span class="text-sm text-slate-800">{{ $ch->label }}</span>

                    @if($showResult && $isCorrectChoice)
                      <span class="ml-auto text-xs text-emerald-700 font-medium">Bonne réponse ✓</span>
                    @endif
                  </label>
                @endforeach
              </div>

            {{-- TEXTE LIBRE (non noté automatiquement) --}}
            @else
              <div class="mt-3">
                <textarea name="answers_text[{{ $q->id }}]" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:ring-emerald-500"
                          placeholder="Votre réponse…" {{ $disableWhenScored }}>{{ old("answers_text.$q->id", $selected->first()) }}</textarea>
              </div>
            @endif

            {{-- Bandeau Correct/Incorrect + récap des bonnes réponses --}}
            @if($showResult)
              <div class="mt-3 text-sm flex flex-wrap items-center gap-2">
                @if($detail['correct'] ?? false)
                  <span class="inline-flex items-center rounded-md bg-emerald-100 text-emerald-800 px-2 py-1">Correct</span>
                @else
                  <span class="inline-flex items-center rounded-md bg-rose-100 text-rose-800 px-2 py-1">Incorrect</span>
                @endif

                {{-- Liste des bonnes réponses --}}
                <span class="text-slate-600">
                  Bonne(s) réponse(s) :
                  <strong>
                    {{ $q->choices->whereIn('id', $correctOpts)->pluck('label')->implode(', ') }}
                  </strong>
                </span>
              </div>
            @endif

          </div>
        </div>
      </article>
    @endforeach

    <div class="flex items-center justify-end">
      <button type="submit"
              class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-white font-medium hover:bg-emerald-500">
        Valider mes réponses
      </button>
    </div>
  </form>
</div>
@endsection
