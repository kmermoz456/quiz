@extends('layouts.app')
@section('title','Mes résultats')
@section('content')
<h1 class="text-xl font-semibold mb-4">Mes résultats</h1>
<table class="w-full text-sm">
<thead class="text-left border-b border-white/10"><tr><th>Examen</
th><th>Sujet</th><th>Score</th><th>Soumis</th></tr></thead>
<tbody>
@forelse($attempts as $a)
<tr class="border-b border-white/5">
<td class="py-2">{{ $a->exam->title }}</td>
<td>{{ $a->exam->subject->title }}</td>
<td>{{ $a->score }}/{{ $a->max_score }} ({{ $a->max_score?
round(100*$a->score/$a->max_score):0 }}%)</td>
<td>{{ optional($a->submitted_at)->format('d/m/Y H:i') }}</td>
</tr>
@empty
<tr><td colspan="4" class="py-4">Aucun résultat.</td></tr>
@endforelse
</tbody>
</table>
@endsection