 {{-- FOOTER --}}
  <footer class="border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 text-sm text-slate-600 flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between">
      <div>© {{ date('Y') }} ITF-Évaluation. Tous droits réservés.</div>
      <nav class="flex gap-4">
        <a class="hover:text-green-600" href="#">Mentions légales</a>
        <a class="hover:text-green-600" href="{{ route('admin.dashboard') }}">Gestion</a>
      </nav>
    </div>
  </footer>