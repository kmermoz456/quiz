<header x-data="{ open:false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white/80 backdrop-blur">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="h-16 flex items-center justify-between gap-3">

      {{-- Logo + brand --}}
      <a href="{{ route('home') }}" class="group flex items-center gap-2">
        <img class="w-8 h-8 rounded-lg ring-1 ring-slate-200/60" src="{{ asset('icon.png') }}" alt="logo">
        <span class="font-semibold tracking-tight">
          ITF<span class="text-green-600">-</span>Évaluation
        </span>
      </a>

      {{-- NAV desktop --}}
      <nav class="hidden md:flex items-center gap-1">
        @php
          $nav = [
            ['label'=>'Accueil','route'=>'home','match'=>'home'],
            ['label'=>'Licence 1','route'=>'subjects.l1','match'=>'subjects.l1'],
            ['label'=>'Licence 2','route'=>'subjects.l2','match'=>'subjects.l2'],
            ['label'=>'Examens','route'=>'student.exams.index','match'=>'student.exams.*'],
          ];
        @endphp

        @foreach($nav as $item)
          @php $active = request()->routeIs($item['match']); @endphp
          <a href="{{ route($item['route']) }}"
             class="relative px-3 py-2 text-sm transition-colors
                    {{ $active ? 'text-green-700' : 'text-slate-700 hover:text-green-700' }}">
            <span>{{ $item['label'] }}</span>
            {{-- underline animée --}}
            <span class="absolute left-2 right-2 -bottom-0.5 h-[2px] rounded-full transition-all
                         {{ $active ? 'bg-green-600' : 'bg-transparent group-hover:bg-green-200' }}"></span>
          </a>
        @endforeach
      </nav>

      {{-- Actions desktop --}}
      <div class="hidden sm:flex items-center gap-2">
        @guest
          <a href="{{ route('login') }}"
             class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-700 hover:bg-slate-50">
            Se connecter
          </a>
          <a href="{{ route('register') }}"
             class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-white
                    bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-500 hover:to-emerald-500 shadow-sm">
            Créer un compte
          </a>
        @endguest

        @auth
          <a href="{{ route('dashboard') }}"
             class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium
                    {{ request()->routeIs('dashboard') 
                       ? 'bg-green-600 text-white shadow-sm'
                       : 'text-white bg-green-600 hover:bg-green-500 shadow-sm' }}">
            Tableau de bord
          </a>

          {{-- Avatar + menu rapide --}}
          <div class="ml-1 flex items-center gap-2 pl-3 border-l border-slate-200">
            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-emerald-500 to-green-600 grid place-content-center text-white text-xs">
              {{ Str::of(Auth::user()->name)->explode(' ')->map(fn($p)=>Str::substr($p,0,1))->take(2)->implode('') }}
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="ml-1">
              @csrf
              <button type="submit"
                class="inline-flex items-center px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-700 hover:bg-slate-50">
                Déconnexion
              </button>
            </form>
          </div>
        @endauth
      </div>

      {{-- Hamburger (mobile) --}}
      <button @click="open=!open"
              class="md:hidden p-2 rounded-lg border border-slate-200 hover:bg-slate-50"
              aria-label="Ouvrir le menu">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>

  {{-- Drawer mobile --}}
  <div class="md:hidden border-t border-slate-200" x-show="open" x-transition.origin.top.left @click.outside="open=false">
    <nav class="px-4 sm:px-6 py-3 space-y-1 bg-white">
      <a href="{{ route('home') }}"
         class="block px-3 py-2 rounded-lg text-sm
           {{ request()->routeIs('home') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-slate-50 text-slate-700' }}">
        Accueil
      </a>
      <a href="{{ route('subjects.l1') }}"
         class="block px-3 py-2 rounded-lg text-sm
           {{ request()->routeIs('subjects.l1') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-slate-50 text-slate-700' }}">
        Licence 1
      </a>
      <a href="{{ route('subjects.l2') }}"
         class="block px-3 py-2 rounded-lg text-sm
           {{ request()->routeIs('subjects.l2') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-slate-50 text-slate-700' }}">
        Licence 2
      </a>
      <a href="{{ route('student.exams.index') }}"
         class="block px-3 py-2 rounded-lg text-sm
           {{ request()->routeIs('student.exams.*') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-slate-50 text-slate-700' }}">
        Examens
      </a>

      <div class="pt-2 space-y-2">
        @guest
          <a href="{{ route('login') }}"
             class="block w-full text-center px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-700 hover:bg-slate-50">
            Connexion
          </a>
          <a href="{{ route('register') }}"
             class="block w-full text-center px-3 py-2 rounded-xl text-sm text-white
                    bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-500 hover:to-emerald-500 shadow-sm">
            Création de compte
          </a>
        @endguest

        @auth
          <a href="{{ route('dashboard') }}"
             class="block w-full text-center px-3 py-2 rounded-xl text-sm font-medium
                    {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-700' : 'bg-green-600 text-white hover:bg-green-500' }}">
            Tableau de bord
          </a>
          <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
              class="block w-full text-center px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-700 hover:bg-slate-50">
              Déconnexion
            </button>
          </form>
        @endauth
      </div>
    </nav>
  </div>
</header>
