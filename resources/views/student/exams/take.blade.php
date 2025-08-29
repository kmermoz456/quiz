@extends('layouts.app')
@section('title',$exam->title)
@section('content')
<div x-data="examTimer({{ $timeLeft }})" class="space-y-6">
    <div class="flex justify-between items-center">
<h1 class="text-xl font-semibold">{{ $exam->title }}</h1>
<div class="px-3 py-1 rounded-lg bg-white/10">Temps restant : <span xtext="fmt()"></span></div>
</div>
<form method="POST" action="{{ route('student.exams.submit',$exam) }}"
@submit="lock=true">
@csrf
<div class="space-y-4">
@foreach($questions as $q)
<article class="rounded-xl border border-white/10 p-4">
<div class="font-medium mb-2">{{ $loop->iteration }}. {!!
nl2br(e($q->statement)) !!} ({{ $q->points }} pt)</div>
<div class="space-y-2">
@foreach($q->choices as $c)
@php $name = "answers[{$q->id}][]"; @endphp
<label class="flex items-center gap-2">
<input class="accent-emerald-500" type="{{ $q-
>type==='single'?'radio':'checkbox' }}" name="{{ $name }}" value="{{ $c-
>id }}">
<span>{{ $c->label }}</span>
</label>
@endforeach
</div>
</article>
@endforeach
</div>
<div class="pt-4">
<button :disabled="lock" class="px-4 py-2 rounded-lg bg-emerald-600
hover:bg-emerald-500">Soumettre</button>
</div>
</form>
</div>
<script>
function examTimer(seconds){
return { s: seconds, lock:false,
fmt(){ const m=Math.floor(this.s/60), ss=this.s%60; return `${m}:$
{ss.toString().padStart(2,'0')}` },
tick(){ if(this.s>0){ this.s--; if(this.s===0)
{ document.querySelector('form').submit(); } } },
init(){ setInterval(()=>this.tick(),1000); }
}
}
</script>
@endsection