{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.admin-dark')
@section('title','Dashboard')

@section('content')
<div class="grid md:grid-cols-5 gap-6">
  {{-- CARTES KPI --}}
  <div class="md:col-span-5 grid sm:grid-cols-2 xl:grid-cols-5 gap-6">
    <div class="rounded-2xl p-5 bg-gradient-to-br from-emerald-600 to-emerald-500 shadow text-white">
      <div class="text-sm/5 opacity-80">Unités</div>
      <div class="text-2xl font-bold">{{ $unitsCount }}</div>
    </div>
    <div class="rounded-2xl p-5 bg-gradient-to-br from-sky-600 to-sky-500 shadow text-white">
      <div class="text-sm/5 opacity-80">Sujets</div>
      <div class="text-2xl font-bold">{{ $subjectsCount }}</div>
    </div>
    <div class="rounded-2xl p-5 bg-gradient-to-br from-purple-600 to-purple-500 shadow text-white">
      <div class="text-sm/5 opacity-80">Questions</div>
      <div class="text-2xl font-bold">{{ $questionsCount }}</div>
    </div>
    <div class="rounded-2xl p-5 bg-gradient-to-br from-pink-600 to-pink-500 shadow text-white">
      <div class="text-sm/5 opacity-80">Examens</div>
      <div class="text-2xl font-bold">{{ $examsCount }}</div>
    </div>
    <div class="rounded-2xl p-5 bg-gradient-to-br from-amber-600 to-amber-500 shadow text-white">
      <div class="text-sm/5 opacity-80">Étudiants</div>
      <div class="text-2xl font-bold">{{ $studentsCount }}</div>
    </div>
  </div>

  {{-- CHARTS (cartes claires) --}}
  <section class="md:col-span-3 rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
    <h3 class="text-lg font-semibold mb-3 text-slate-900">Tentatives par mois</h3>
    <canvas id="lineAttempts" height="120"></canvas>
  </section>

  <section class="md:col-span-2 rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
    <h3 class="text-lg font-semibold mb-3 text-slate-900">Répartition des entités</h3>
    <canvas id="doughnutEntities" height="120"></canvas>
  </section>

  {{-- TABLES --}}
  <section class="md:col-span-3 rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
    <h3 class="text-lg font-semibold mb-3 text-slate-900">Dernières tentatives</h3>
    <table class="w-full text-sm">
      <thead class="text-left text-slate-500 border-b border-slate-200">
        <tr>
          <th class="py-2">Étudiant</th>
          <th>Examen</th>
          <th>Score</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody class="text-slate-700">
        @forelse($latestAttempts as $a)
          <tr class="border-b border-slate-100">
            <td class="py-2">
              {{ $a->user->name ?? '—' }}
              <span class="text-slate-400">{{ $a->user->level ?? '' }}</span>
            </td>
            <td>{{ $a->exam->title ?? '—' }}</td>
            <td class="{{ $a->score!==null ? 'text-emerald-600' : 'text-slate-400' }}">
              {{ $a->score !== null ? $a->score.'%' : '—' }}
            </td>
            <td class="text-slate-500">{{ $a->created_at->format('d/m/Y H:i') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="py-6 text-slate-500">Aucune tentative récente.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </section>

  <section class="md:col-span-2 rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
    <h3 class="text-lg font-semibold mb-3 text-slate-900">Examens récents</h3>
    <ul class="space-y-2">
      @foreach($latestExams as $e)
        <li class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between">
          <span class="text-slate-800">{{ $e->title }}</span>
          <a href="{{ route('admin.exams.edit',$e) }}" class="text-sm text-emerald-600 hover:underline">Gérer</a>
        </li>
      @endforeach
    </ul>
  </section>
</div>

{{-- Charts init (couleurs adaptées au thème clair) --}}
<script>

  
document.addEventListener('DOMContentLoaded', () => {
  // Tentatives / mois
   const monthly = @json($seriesMonthly);   // pas de map côté PHP ici
  const labels  = monthly.map(r => r.m);
  const data    = monthly.map(r => r.c);

  new Chart(document.getElementById('lineAttempts'), {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Tentatives',
        data,
        borderColor: '#10b981',       // emerald-500
        backgroundColor: 'rgba(16,185,129,.15)',
        pointBackgroundColor: '#10b981',
        tension: .35,
      }]
    },
    options: {
      plugins: {
        legend: { labels: { color: '#334155' } } // slate-700
      },
      scales: {
        x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(148,163,184,.25)' } }, // slate-500
        y: { ticks: { color: '#64748b' }, grid: { color: 'rgba(148,163,184,.25)' } },
      }
    }
  });

  // Doughnut entités
  new Chart(document.getElementById('doughnutEntities'), {
    type: 'doughnut',
    data: {
      labels: ['UE','Sujets','Questions','Examens','Étudiants'],
      datasets: [{
        data: [{{ $unitsCount }}, {{ $subjectsCount }}, {{ $questionsCount }}, {{ $examsCount }}, {{ $studentsCount }}],
        backgroundColor: ['#10b981','#0ea5e9','#8b5cf6','#ec4899','#f59e0b'],
      }]
    },
    options: {
      plugins: { legend: { labels: { color: '#334155' } } }
    }
  });
});
</script>
@endsection
