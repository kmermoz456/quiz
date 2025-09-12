{{-- resources/views/dashboard/student.blade.php --}}
@extends('layouts.app')
@section('title','Tableau de bord')

@section('content')
<div class="max-w-5xl mx-auto p-6">
  {{-- Flash --}}
  @if(session('error'))
    <div class="mb-4 rounded-lg bg-rose-50 text-rose-800 px-4 py-3">{{ session('error') }}</div>
  @endif
  @if(session('status'))
    <div class="mb-4 rounded-lg bg-emerald-50 text-emerald-800 px-4 py-3">{{ session('status') }}</div>
  @endif

  <div class="grid md:grid-cols-3 gap-6 mb-6">
    <div class="rounded-2xl p-5 bg-gradient-to-br from-emerald-600 to-emerald-500 text-white shadow">
      <div class="text-sm">Tentatives</div>
      <div class="text-2xl font-bold">{{ $attemptsCount }}</div>
    </div>
    <div class="rounded-2xl p-5 bg-gradient-to-br from-sky-600 to-sky-500 text-white shadow">
      <div class="text-sm">Moyenne</div>
      <div class="text-2xl font-bold">{{ $avgScore }}%</div>
    </div>
    <div class="rounded-2xl p-5 bg-gradient-to-br from-purple-600 to-purple-500 text-white shadow">
      <div class="text-sm">Bonjour</div>
      <div class="text-2xl font-bold">{{ $user->name }} / {{ $user->level }}</div>
      
    </div>
  </div>

  <div class="grid md:grid-cols-2 gap-6">
    {{-- Profil (édition rapide) --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5">
      <h2 class="text-lg font-semibold mb-3">Mon profil</h2>
      <form method="POST" action="{{ route('profile.update') }}" class="space-y-3">
        @csrf @method('PATCH')
        <div>
          <label class="text-sm text-slate-600">Nom</label>
          <input type="text" name="name" value="{{ old('name',$user->name) }}"
                 class="mt-1 w-full rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500"/>
        </div>
        <div>
          <label class="text-sm text-slate-600">Email</label>
          <input type="email" value="{{ $user->email }}" disabled
                 class="mt-1 w-full rounded-lg border-slate-300 bg-slate-50"/>
        </div>
        <button class="mt-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">Enregistrer</button>
      </form>
    </section>

    {{-- Dernières tentatives --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5">
      <h2 class="text-lg font-semibold mb-3">Mes dernières tentatives</h2>
      <ul class="divide-y divide-slate-200 text-sm">
        @forelse($lastAttempts as $a)
          <li class="py-3 flex items-center justify-between">
            <div>
              <div class="font-medium">{{ $a->exam->title ?? '—' }}</div>
              <div class="text-slate-500">{{ $a->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="font-semibold {{ $a->score ? 'text-emerald-600' : 'text-slate-400' }}">
              {{ $a->score !== null ? $a->score.'%' : '—' }}
            </div>
          </li>
        @empty
          <li class="py-6 text-slate-500">Aucune tentative pour l’instant.</li>
        @endforelse
      </ul>
    </section>
  </div>
</div>
@endsection
