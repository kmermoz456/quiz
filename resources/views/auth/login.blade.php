{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')
  
@section('content')
  <div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-amber-50 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
      {{-- Logo / Titre --}}
      <div class="text-center mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
          <span class="inline-block h-8 w-8 rounded bg-green-600"></span>
          <span class="text-lg font-semibold text-slate-800">ITF-Évaluation</span>
        </a>
        <h1 class="mt-3 text-2xl font-semibold text-slate-900">Connexion</h1>
        <p class="text-sm text-slate-600">Ravi de vous revoir !</p>
      </div>

      {{-- Carte --}}
      <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-100 p-6">
        {{-- Status de session (ex: “Mot de passe réinitialisé”) --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
          @csrf

          {{-- Email --}}
          <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input
              id="email"
              type="email"
              name="email"
              value="{{ old('email') }}"
              required
              autofocus
              autocomplete="username"
              class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-green-500 focus:ring-green-500"
              placeholder="vous@exemple.com"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          {{-- Mot de passe --}}
          <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe</label>
            <input
              id="password"
              type="password"
              name="password"
              required
              autocomplete="current-password"
              class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-green-500 focus:ring-green-500"
              placeholder="••••••••"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          {{-- Souvenir de moi + lien mot de passe oublié --}}
          <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2">
              <input id="remember_me" type="checkbox" name="remember"
                     class="h-4 w-4 rounded border-slate-300 text-green-600 focus:ring-green-500">
              <span class="text-sm text-slate-700">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}"
                 class="text-sm text-green-600 hover:text-green-500">
                Mot de passe oublié ?
              </a>
            @endif
          </div>

          {{-- Bouton Connexion --}}
          <button
            type="submit"
            class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-white font-medium hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
          >
            Se connecter
          </button>
        </form>

        {{-- Divider --}}
        <div class="my-5 flex items-center gap-3">
          <div class="h-px w-full bg-slate-200"></div>
          <span class="text-xs text-slate-500">ou</span>
          <div class="h-px w-full bg-slate-200"></div>
        </div>

        {{-- Lien inscription --}}
        <p class="text-center text-sm text-slate-600">
          Pas encore de compte ?
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-500">
              Créer un compte
            </a>
          @endif
        </p>
      </div>

      {{-- Mention pied --}}
      <p class="mt-6 text-center text-xs text-slate-500">
        © {{ date('Y') }} ITF-Évaluation. Tous droits réservés.
      </p>
    </div>
  </div>

@endsection