@extends('layouts.app')
@section('title', 'Réponses envoyées')

@section('content')

@if(session('status'))
  <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3">
    {{ session('status') }}
  </div>
@endif

<div class="p-5">
  <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center">
    <h1 class="text-2xl font-semibold text-slate-900">Réponses envoyées ✅</h1>
    <p class="mt-2 text-slate-600">
      Votre copie a bien été transmise. Les résultats seront publiés par l’administration.
    </p>
    <a href="{{ route('home') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-500">
      Retour à l’accueil
    </a>
  </div>
</div>
@endsection
