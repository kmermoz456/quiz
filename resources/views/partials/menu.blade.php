<header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="h-16 flex items-center justify-between">
      <a href="{{ route('home') }}" class="flex items-center gap-2">
        <div class="h-6 w-6 rounded bg-green-600"></div>
        <span class="font-semibold">ITF-Évaluation</span>
      </a>

      {{-- NAV desktop --}}
      <nav class="hidden md:flex items-center gap-6 text-sm">
        <a href="{{ route('home') }}"
           class="{{ request()->routeIs('home') ? 'text-green-600 font-semibold' : 'hover:text-green-600' }}">
          Accueil
        </a>
        <a href="{{ route('subjects.l1') }}"
           class="{{ request()->routeIs('subjects.l1') ? 'text-green-600 font-semibold' : 'hover:text-green-600' }}">
          Licence 1
        </a>
        <a href="{{ route('subjects.l2') }}"
           class="{{ request()->routeIs('subjects.l2') ? 'text-green-600 font-semibold' : 'hover:text-green-600' }}">
          Licence 2
        </a>
        <a href="{{ route('student.exams.index') }}"
           class="{{ request()->routeIs('student.exams.*') ? 'text-green-600 font-semibold' : 'hover:text-green-600' }}">
          Examens
        </a>
      </nav>

      {{-- Actions desktop --}}
      <div class="hidden sm:flex items-center gap-3">
        @guest
          <a href="{{ route('login') }}" 
             class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-sm">
            Connexion
          </a>
          <a href="{{ route('register') }}" 
             class="px-3 py-2 rounded-lg bg-green-600 text-white text-sm hover:bg-green-500">
            Création de compte
          </a>
        @endguest

        @auth
          <span class="text-sm text-slate-700">👋 Bonjour, <strong>{{ Auth::user()->name }}</strong></span>
          <a href="{{ route('dashboard') }}" 
             class="{{ request()->routeIs('dashboard') ? 'bg-green-600 text-white font-semibold' : 'bg-green-600 text-white hover:bg-green-500' }} px-3 py-2 rounded-lg text-sm">
            Tableau de bord
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-sm">
              Déconnexion
            </button>
          </form>
        @endauth
      </div>

      {{-- Bouton hamburger (mobile) --}}
      <button @click="open=!open" class="md:hidden p-2 rounded-lg border border-slate-200" aria-label="Ouvrir le menu">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>

  {{-- NAV mobile --}}
  <div class="md:hidden" x-show="open" x-transition.origin.top.left @click.outside="open=false">
    <nav class="px-4 sm:px-6 pb-4 space-y-1">
      <a href="{{ route('home') }}"
         class="block px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-slate-50' }}">
        Accueil
      </a>
      <a href="{{ route('subjects.l1') }}"
         class="block px-3 py-2 rounded-lg {{ request()->routeIs('subjects.l1') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-slate-50' }}">
        Licence 1
      </a>
      <a href="{{ route('subjects.l2') }}"
         class="block px-3 py-2 rounded-lg {{ request()->routeIs('subjects.l2') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-slate-50' }}">
        Licence 2
      </a>
      <a href="{{ route('student.exams.index') }}"
         class="block px-3 py-2 rounded-lg {{ request()->routeIs('student.exams.*') ? 'bg-green-100 text-green-700 font-semibold' : 'hover:bg-slate-50' }}">
        Examens
      </a>

      <div class="pt-2 flex flex-col gap-2">
        @guest
          <a href="{{ route('login') }}" class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-sm w-full text-center">
            Connexion
          </a>
          <a href="{{ route('register') }}" class="px-3 py-2 rounded-lg bg-green-600 text-white text-sm hover:bg-green-500 w-full text-center">
            Création de compte
          </a>
        @endguest

        @auth
          <span class="block text-sm text-slate-700 text-center">👋 Bonjour, <strong>{{ Auth::user()->name }}</strong></span>
          <a href="{{ route('dashboard') }}"
             class="px-3 py-2 rounded-lg w-full text-center {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-700 font-semibold' : 'bg-green-600 text-white hover:bg-green-500' }}">
            Tableau de bord
          </a>
          <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="px-3 py-2 w-full rounded-lg border border-slate-200 hover:bg-slate-50 text-sm text-center">
              Déconnexion
            </button>
          </form>
        @endauth
      </div>
    </nav>
  </div>
</header>
