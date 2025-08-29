{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')
  
@section('content')
  {{-- resources/views/auth/register.blade.php --}}

  <div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-amber-50 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-lg">
      {{-- Logo + Titre --}}
      <div class="text-center mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
          <span class="inline-block h-8 w-8 rounded bg-green-600"></span>
          <span class="text-lg font-semibold text-slate-800">ITF-Évaluation</span>
        </a>
        <h1 class="mt-3 text-2xl font-semibold text-slate-900">Créer un compte</h1>
        <p class="text-sm text-slate-600">Rejoignez la plateforme et commencez vos quiz.</p>
      </div>

      {{-- Carte --}}
      <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-100 p-6">
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
          @csrf

          {{-- Nom complet --}}
          <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Nom complet</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-green-500 focus:ring-green-500"
                   placeholder="Jean Dupont">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
          </div>

          {{-- Téléphone --}}
          <div>
            <label for="phone" class="block text-sm font-medium text-slate-700">Téléphone</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                   class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-green-500 focus:ring-green-500"
                   placeholder="+225 07 00 00 00">
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
          </div>

          {{-- Email --}}
          <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                   class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-green-500 focus:ring-green-500"
                   placeholder="vous@exemple.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          {{-- Niveau (L1/L2) --}}
          <div>
            <label for="level" class="block text-sm font-medium text-slate-700">Niveau</label>
            <select id="level" name="level"
                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-green-500 focus:ring-green-500">
              <option value="">-- Sélectionnez --</option>
              <option value="L1" {{ old('level')=='L1' ? 'selected' : '' }}>Licence 1</option>
              <option value="L2" {{ old('level')=='L2' ? 'selected' : '' }}>Licence 2</option>
            </select>
            <x-input-error :messages="$errors->get('level')" class="mt-2" />
          </div>

                 

          {{-- Mot de passe --}}
          <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-green-500 focus:ring-green-500"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          {{-- Confirmation mot de passe --}}
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirmer le mot de passe</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm focus:border-green-500 focus:ring-green-500"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
          </div>

          {{-- Bouton --}}
          <button type="submit"
                  class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-white font-medium hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            S’inscrire
          </button>
        </form>

        {{-- Lien connexion --}}
        <p class="mt-5 text-center text-sm text-slate-600">
          Déjà inscrit ?
          <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500">Se connecter</a>
        </p>
      </div>

      {{-- Pied --}}
      <p class="mt-6 text-center text-xs text-slate-500">
        © {{ date('Y') }} ITF-Évaluation. Tous droits réservés.
      </p>
    </div>
  </div>



@endsection