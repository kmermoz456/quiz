@extends('layouts.admin-dark')
@section('title','Étudiants')

@section('content')
<h1 class="text-2xl font-bold mb-6">📚 Liste des étudiants</h1>

<div class="bg-white rounded-2xl shadow border border-slate-200 overflow-hidden">
  <table class="w-full text-sm text-left">
    <thead class="bg-slate-100 text-slate-700 text-xs uppercase tracking-wide">
      <tr>
        <th class="px-4 py-3">Nom</th>
        <th class="px-4 py-3">Email</th>
        <th class="px-4 py-3">Contact</th>
        <th class="px-4 py-3">Niveau</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
      @forelse($students as $u)
      <tr class="hover:bg-slate-50 transition">
        <td class="px-4 py-3 font-medium text-slate-900">{{ $u->name }}</td>
        <td class="px-4 py-3 text-slate-600">{{ $u->email }}</td>
        <td class="px-4 py-3 text-slate-600">{{ $u->phone }}</td>
        <td class="px-4 py-3">
          @if($u->level === 'L1')
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Licence 1</span>
          @elseif($u->level === 'L2')
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-sky-100 text-sky-700">Licence 2</span>
          @else
            <span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-500">—</span>
          @endif
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="3" class="px-4 py-6 text-center text-slate-500">Aucun étudiant inscrit.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
    {{ $students->links() }}
  </div>
</div>
@endsection
