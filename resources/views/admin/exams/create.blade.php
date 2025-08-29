@extends('layouts.admin-dark')
@section('title','Nouvel examen')
@section('content')
@include('admin.partials.flash')
<div class="rounded-2xl border bg-white p-5">
  <h1 class="text-xl font-semibold mb-4">Créer un examen</h1>
  <form action="{{ route('admin.exams.store') }}" method="POST">
    @include('admin.exams._form')
  </form>
</div>
@endsection
