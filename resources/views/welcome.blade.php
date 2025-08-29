@extends('layouts.app')
  
@section('content')

  {{-- HERO / BANNIÈRE --}}
  <section class="relative">
    <div class="absolute inset-0">
      {{-- Image libre Unsplash --}}
      <img
        src="https://plus.unsplash.com/premium_photo-1682146165966-d5a09ac844fa?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
        alt="Étudiants en train d'apprendre"
        class="w-full h-full object-cover"
        loading="eager"
        fetchpriority="high"
      >
      <div class="absolute inset-0 bg-gradient-to-r from-green-900/70 via-green-800/50 to-amber-700/40"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-14 md:py-24">
      <div class="grid lg:grid-cols-2 gap-10 items-center">
        <div class="text-white">
          <h1 class="text-3xl sm:text-5xl font-bold leading-tight">
            Entraînez-vous. Progressez. Réussissez.
          </h1>
          <p class="mt-4 text-white/90 max-w-xl">
            Pratiquez via des quiz ciblés et des examens en ligne pour booster vos résultats en Licence 1 & 2.
          </p>
          <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-amber-400 text-slate-900 font-medium hover:bg-amber-300">
              Créer mon compte
            </a>
            <a href="{{ route('student.exams.index') }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/30 text-white hover:bg-white/20">
              Voir les examens
            </a>
          </div>
        </div>

        {{-- Carte à droite : passe sous le texte sur mobile --}}
        <div class="lg:justify-self-end">
          <div class="rounded-xl bg-white/90 backdrop-blur border border-white/30 shadow-xl p-6 max-w-md mx-auto">
            <h3 class="font-semibold text-slate-900">Démarrez en 3 étapes</h3>
            <ol class="mt-3 space-y-2 text-slate-700 text-sm">
              <li>1. Créez votre compte étudiant.</li>
              <li>2. Choisissez votre niveau (L1 ou L2) et un sujet.</li>
              <li>3. Lancez un quiz, recevez un score et vos corrections.</li>
            </ol>
            <a href="{{ route('register') }}" class="mt-4 inline-flex px-3 py-2 rounded-lg bg-green-600 text-white text-sm hover:bg-green-500">Je m’inscris</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- POURQUOI LES QUIZ SONT IMPORTANTS --}}
  <section class="py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <h2 class="text-2xl sm:text-3xl font-semibold text-center">Pourquoi les quiz accélèrent vos apprentissages</h2>
      <p class="mt-2 text-center text-slate-600 max-w-3xl mx-auto">
        Courts, réguliers et ciblés : les quiz transforment la mémorisation et donnent un feedback immédiat.
      </p>

      {{-- Grid responsive: 1 → 2 → 4 colonnes --}}
      <div class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="h-12 w-12 rounded-lg bg-amber-100 flex items-center justify-center">
            <span class="text-amber-600 text-xl">🎯</span>
          </div>
          <h3 class="mt-4 font-medium">Motivation continue</h3>
          <p class="mt-2 text-sm text-slate-600">Des objectifs courts et mesurables qui maintiennent l’engagement.</p>
        </div>

        <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
            <span class="text-green-600 text-xl">🧠</span>
          </div>
          <h3 class="mt-4 font-medium">Mémorisation active</h3>
          <p class="mt-2 text-sm text-slate-600">Répétition espacée et rappel actif pour ancrer durablement.</p>
        </div>

        <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="h-12 w-12 rounded-lg bg-emerald-100 flex items-center justify-center">
            <span class="text-emerald-600 text-xl">⚡</span>
          </div>
          <h3 class="mt-4 font-medium">Feedback immédiat</h3>
          <p class="mt-2 text-sm text-slate-600">Comprenez vos erreurs maintenant, pas la veille de l’examen.</p>
        </div>

        <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
            <span class="text-purple-600 text-xl">📈</span>
          </div>
          <h3 class="mt-4 font-medium">Préparation aux examens</h3>
          <p class="mt-2 text-sm text-slate-600">Simulez les conditions réelles et suivez votre progression.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA FINAL --}}
  <section class="py-10 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
      <h3 class="text-xl sm:text-2xl font-semibold">Prêt(e) à vous entraîner ?</h3>
      <div class="mt-4 flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('student.exams.index') }}" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-500">Lancer un quiz</a>
        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-white">Créer un compte</a>
      </div>
    </div>
  </section>

 @endsection