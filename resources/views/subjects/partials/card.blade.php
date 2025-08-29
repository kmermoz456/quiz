@php
  $questionsCount = $subject->questions_count ?? $subject->questions()->count();
  $date = optional($subject->created_at)->locale('fr_FR')->translatedFormat('d F Y');

  if(auth()->check()){
      // Lien d’entraînement (questions du sujet)
      $targetUrl = route('subjects.practice', $subject);
      $ctaLabel  = 'S’entraîner';
  } else {
      // Si non connecté, on renvoie vers login
      $targetUrl = route('login');
      $ctaLabel  = 'Se connecter pour s’entraîner';
  }
@endphp

<a href="{{ $targetUrl }}"
   class="group block rounded-3xl overflow-hidden bg-white shadow-sm border border-slate-200 hover:shadow-lg hover:-translate-y-0.5 transition">
  
  {{-- Illustration (optionnelle) --}}
  @if(!empty($subject->thumbnail_url))
    <div class="aspect-[16/9] w-full overflow-hidden">
      <img src="{{ $subject->thumbnail_url }}" 
           alt="Illustration {{ $subject->title }}" 
           class="h-full w-full object-cover group-hover:scale-105 transition">
    </div>
  @endif

  <div class="p-6">
    {{-- Badges --}}
    <div class="flex items-center gap-2 text-xs text-slate-500">
      @if($subject->unit)
        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5">
          UE {{ $subject->unit->code }}
        </span>
      @endif
      <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5">
        {{ $subject->level }}
      </span>
      <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5">
        {{ $questionsCount }} question(s)
      </span>
    </div>

    {{-- Titre --}}
    <h2 class="mt-3 text-xl font-extrabold leading-snug text-slate-900 group-hover:text-emerald-700">
      {{ $subject->title }}
    </h2>

    {{-- Date --}}
    @if($date)
      <div class="mt-1 text-sm text-slate-500">Publié le {{ $date }}</div>
    @endif

    {{-- Description --}}
    <p class="mt-3 text-slate-700 text-[15px] leading-relaxed line-clamp-3">
      {{ $subject->description ?: 'Découvrez et entraînez-vous avec des quiz sur ce sujet.' }}
    </p>

    {{-- Call-to-action --}}
    <div class="mt-4">
      <span class="inline-flex items-center gap-2 text-emerald-600 font-semibold group-hover:text-emerald-500">
        {{ $ctaLabel }}
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </span>
    </div>
  </div>
</a>
