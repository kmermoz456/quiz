@extends('layouts.admin-dark')
@section('title','Modifier examen')
@section('content')
@include('admin.partials.flash')
<div class="rounded-2xl border bg-white p-5">
  <h1 class="text-xl font-semibold mb-4">Modifier l’examen</h1>
  <form action="{{ route('admin.exams.update',$exam) }}" method="POST">
    @method('PUT')
    @include('admin.exams._form')
  </form>
</div>
@endsection
