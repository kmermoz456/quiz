@extends('layouts.admin-dark')
@section('title','Modifier question')
@section('content')
@include('admin.partials.flash')
<div class="rounded-2xl border bg-white p-5">
  <h1 class="text-xl font-semibold mb-4">Modifier la question</h1>
  <form action="{{ route('admin.questions.update',$question) }}" method="POST">
    @method('PUT')
    @include('admin.questions._form')
  </form>
</div>
@endsection
