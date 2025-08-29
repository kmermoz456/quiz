{{-- resources/views/layouts/admin.blade.php --}}
<!doctype html>
<html lang="fr" x-data="{ openSidebar: true }" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Admin')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="h-full bg-slate-50 text-slate-900">
<div class="flex h-screen">
  {{-- SIDEBAR --}}
  <aside :class="openSidebar ? 'w-64' : 'w-16'"
         class="transition-all duration-200 bg-white border-r border-slate-200 flex flex-col shadow-sm">
    <div class="h-16 px-4 flex items-center gap-3 border-b border-slate-200">
      <button @click="openSidebar=!openSidebar" class="p-2 rounded hover:bg-slate-100" aria-label="Toggle menu">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <span x-show="openSidebar" class="font-semibold tracking-wide">ITF Admin</span>
    </div>

    <nav class="flex-1 p-3 space-y-1 text-sm">
      {{-- Dashboard --}}
      <a href="{{ route('admin.dashboard') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100
         {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-700 font-semibold' : '' }}">
         Dashboard
      </a>

      <div class="mt-3 text-xs uppercase text-slate-400 px-3">Gestion</div>

      {{-- Unités --}}
      <a href="{{ route('admin.units.index') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100
         {{ request()->routeIs('admin.units.*') ? 'bg-green-100 text-green-700 font-semibold' : '' }}">
         Unités (UE)
      </a>

      {{-- Sujets --}}
      <a href="{{ route('admin.subjects.index') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100
         {{ request()->routeIs('admin.subjects.*') ? 'bg-green-100 text-green-700 font-semibold' : '' }}">
         Sujets
      </a>

      {{-- Questions --}}
      <a href="{{ route('admin.questions.index') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100
         {{ request()->routeIs('admin.questions.*') ? 'bg-green-100 text-green-700 font-semibold' : '' }}">
         Questions
      </a>

      {{-- Examens --}}
      <a href="{{ route('admin.exams.index') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100
         {{ request()->routeIs('admin.exams.*') ? 'bg-green-100 text-green-700 font-semibold' : '' }}">
         Examens
      </a>

      {{-- Étudiants (nouveau) --}}
      <a href="{{ route('admin.students.index') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100
         {{ request()->routeIs('admin.students.*') ? 'bg-green-100 text-green-700 font-semibold' : '' }}">
         Étudiants
      </a>

      {{-- Résultats --}}
      <a href="{{ route('admin.results.index') }}"
         class="block px-3 py-2 rounded-lg hover:bg-slate-100
         {{ request()->routeIs('admin.results.*') ? 'bg-green-100 text-green-700 font-semibold' : '' }}">
         Résultats
      </a>
    </nav>

    <div class="p-3 border-t border-slate-200 text-xs text-slate-500">
      Connecté : {{ auth()->user()->name }}
    </div>
  </aside>

  {{-- MAIN --}}
  <div class="flex-1 flex flex-col">
    {{-- TOPBAR --}}
    <header class="h-14 flex items-center justify-between border-b border-slate-200 px-4 bg-white shadow-sm">
      <h1 class="text-lg font-semibold">@yield('title')</h1>
      <div class="flex items-center gap-2">
        <a href="{{ route('home') }}" class="text-sm text-slate-600 hover:underline">Site</a>
        <form action="{{ route('logout') }}" method="POST" class="ml-2">@csrf
          <button class="text-sm text-rose-600 hover:underline">Déconnexion</button>
        </form>
      </div>
    </header>

    <main class="flex-1 overflow-y-auto p-6 bg-slate-50">
      @yield('content')
    </main>
  </div>
</div>
</body>
</html>
