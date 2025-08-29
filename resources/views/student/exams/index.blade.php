@extends('layouts.app')
@section('title','Examens disponibles')
@section('content')
<h1 class="text-xl font-semibold mb-4">Examens ouverts</h1>
<div class="grid sm:grid-cols-2 gap-4">
@forelse($exams as $e)
<article class="rounded-xl border border-white/10 p-4">
<div class="font-medium">{{ $e->title }}</div>
<div class="text-xs text-white/60">{{ $e->subject->title }} — UE {{ $e-
>subject->unit->code }}</div>
<div class="text-sm mt-2">Durée : {{ $e->duration_minutes }} min
@if($e->starts_at) • Débute {{ $e->starts_at->format('d/m H:i') }}
@endif
@if($e->ends_at) • Fin {{ $e->ends_at->format('d/m H:i') }} @endif
</div>
<a href="{{ route('student.exams.start',$e) }}" class="inline-block mt-3
px-3 py-2 bg-emerald-600 rounded">Commencer</a>
</article>
@empty
<p>Aucun examen actuellement.</p>
@endforelse
</div>
@endsection