@extends('layouts.app')
@section('title', $exam->title.' — Examen')

@section('content')
<div class="max-w-5xl mx-auto p-6">

  {{-- Barre sticky avec chrono et titre --}}
  <div class="sticky top-0 z-10 mb-4">
    <div class="rounded-xl border border-slate-200 bg-white/90 backdrop-blur px-4 py-3 shadow">
      <div class="flex items-center justify-between gap-3">
        <div>
          <h1 class="text-lg font-semibold text-slate-900">{{ $exam->title }}</h1>
          <p class="text-xs text-slate-600">
            Durée : {{ $exam->duration_minutes }} min
            @if($exam->starts_at) • Ouvert : {{ $exam->starts_at->format('d/m H:i') }} @endif
            @if($exam->ends_at) • Ferme : {{ $exam->ends_at->format('d/m H:i') }} @endif
          </p>
        </div>
        <div class="text-right">
          <div class="text-xs text-slate-500">Temps restant</div>
          <div id="countdown" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white font-semibold text-lg">
            --:--
          </div>
        </div>
      </div>
      {{-- Barre de progression du temps --}}
      <div class="mt-3 h-2 rounded bg-slate-100 overflow-hidden">
        <div id="timebar" class="h-full bg-emerald-500" style="width:100%"></div>
      </div>
    </div>
  </div>

  {{-- Formulaire : questions affichées immédiatement --}}
  <form id="exam-form" method="POST" action="{{ route('student.exams.submit', $exam) }}" class="space-y-6">
    @csrf
    {{-- sécurité / tentative en cours --}}
    <input type="hidden" name="exam_token" value="{{ $examToken }}">
    <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">

    @foreach($exam->questions as $i => $q)
      <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-start gap-3">
          <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white text-sm">
            {{ $i+1 }}
          </span>
          <div class="w-full">
            <div class="flex items-center gap-2 text-xs text-slate-500">
              <span class="inline-flex rounded bg-slate-100 px-2 py-0.5">{{ strtoupper($q->type) }}</span>
              @if($q->points) <span class="inline-flex rounded bg-slate-100 px-2 py-0.5">{{ $q->points }} pt</span> @endif
            </div>
            <h3 class="mt-1 font-medium text-slate-900">{!! nl2br(e($q->statement)) !!}</h3>

            {{-- SINGLE --}}
            @if($q->type === 'single')
              <div class="mt-3 space-y-2">
                @foreach($q->choices as $ch)
                  <label class="flex items-center gap-2">
                    <input type="radio" name="answers[{{ $q->id }}]" value="{{ $ch->id }}"
                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-sm text-slate-800">{{ $ch->label }}</span>
                  </label>
                @endforeach
              </div>

            {{-- MULTIPLE --}}
            @elseif($q->type === 'multiple')
              <div class="mt-3 space-y-2">
                @foreach($q->choices as $ch)
                  <label class="flex items-center gap-2">
                    <input type="checkbox" name="answers_multi[{{ $q->id }}][]" value="{{ $ch->id }}"
                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-sm text-slate-800">{{ $ch->label }}</span>
                  </label>
                @endforeach
              </div>

            {{-- TRUE/FALSE --}}
            @elseif($q->type === 'true_false')
              <div class="mt-3 space-y-2">
                @php
                  $true = optional($q->choices->firstWhere('label','Vrai'))->id;
                  $false = optional($q->choices->firstWhere('label','Faux'))->id;
                @endphp
                <label class="flex items-center gap-2">
                  <input type="radio" name="answers[{{ $q->id }}]" value="{{ $true ?? '' }}"
                         class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                  <span class="text-sm text-slate-800">Vrai</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" name="answers[{{ $q->id }}]" value="{{ $false ?? '' }}"
                         class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                  <span class="text-sm text-slate-800">Faux</span>
                </label>
              </div>

            {{-- TEXTE --}}
            @else
              <div class="mt-3">
                <textarea name="answers_text[{{ $q->id }}]" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-emerald-500 focus:ring-emerald-500"
                          placeholder="Votre réponse…"></textarea>
              </div>
            @endif
          </div>
        </div>
      </article>
    @endforeach

    <div class="flex items-center justify-between">
      <span class="text-sm text-slate-600">L’envoi est automatique à la fin du temps.</span>
      <button id="manual-submit" type="submit"
              class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2 text-white font-semibold hover:bg-emerald-500">
        Soumettre maintenant
      </button>
    </div>
  </form>
</div>

{{-- Chrono + protections --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form   = document.getElementById('exam-form');
  const btn    = document.getElementById('manual-submit');
  const cdEl   = document.getElementById('countdown');
  const barEl  = document.getElementById('timebar');
  let submitted = false;

  // Durée (ms)
  const durationMs = {{ (int)$exam->duration_minutes }} * 60 * 1000;

  // Point de départ côté serveur (UTC) -> millisecondes
  const startISO  = @json(optional($attempt->started_at)->timezone('UTC')->format('c'));
  const startedAt = startISO ? new Date(startISO).getTime() : Date.now();
  const endAt     = startedAt + durationMs;

  // Utilitaires
  const pad = (n) => String(n).padStart(2,'0');
  const fmt = (ms) => {
    const left = Math.max(0, ms);
    const h = Math.floor(left / 3600000);
    const m = Math.floor((left % 3600000) / 60000);
    const s = Math.floor((left % 60000) / 1000);
    return (h ? pad(h)+':' : '') + pad(m) + ':' + pad(s);
  };

  function setColor(msLeft) {
    cdEl.classList.remove('bg-emerald-600','bg-amber-600','bg-rose-600','animate-pulse');
    barEl.classList.remove('bg-emerald-500','bg-amber-500','bg-rose-500');

    if (msLeft <= 10000) { // < 10s
      cdEl.classList.add('bg-rose-600','animate-pulse');
      barEl.classList.add('bg-rose-500');
    } else if (msLeft <= 60000) { // < 1min
      cdEl.classList.add('bg-amber-600');
      barEl.classList.add('bg-amber-500');
    } else {
      cdEl.classList.add('bg-emerald-600');
      barEl.classList.add('bg-emerald-500');
    }
  }

  function autoSubmit() {
    if (submitted) return;
    submitted = true;
    if (btn) { btn.disabled = true; btn.textContent = 'Envoi en cours…'; }
    window.removeEventListener('beforeunload', beforeUnloadGuard);
    form.submit();
  }

  // Boucle d’animation haute précision
  function tick() {
    const now   = Date.now();
    const left  = Math.max(0, endAt - now);

    // Affichage principal
    cdEl.textContent = fmt(left);
    // Barre de progression
    const pct = Math.max(0, Math.min(100, Math.round((left / durationMs) * 100)));
    barEl.style.width = pct + '%';
    // Couleurs dynamiques
    setColor(left);
    // Titre onglet
    document.title = `${fmt(left)} — {{ $exam->title }}`;

    if (left <= 0) { autoSubmit(); return; }
    requestAnimationFrame(tick);
  }

  // Démarrage immédiat
  tick();

  // Confirmation sur envoi manuel (préserve l’auto-envoi silencieux)
  form.addEventListener('submit', (e) => {
    if (submitted) return;
    const fromManual = e.submitter && e.submitter.id === 'manual-submit';
    if (fromManual) {
      const ok = confirm("Es-tu sûr(e) de vouloir soumettre ta copie ? Tu ne pourras plus modifier tes réponses.");
      if (!ok) { e.preventDefault(); return; }
    }
    submitted = true;
    if (btn) { btn.disabled = true; btn.textContent = 'Envoi en cours…'; }
    window.removeEventListener('beforeunload', beforeUnloadGuard);
  });

  // Protection contre fermeture/refresh avant envoi
  function beforeUnloadGuard(e){
    if (!submitted) { e.preventDefault(); e.returnValue = ''; }
  }
  window.addEventListener('beforeunload', beforeUnloadGuard);
});
</script>

@endsection
