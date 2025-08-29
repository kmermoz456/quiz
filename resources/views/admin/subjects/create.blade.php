@extends('layouts.admin-dark')
@section('title','Nouveau sujet')
@section('content')
@include('admin.partials.flash')
<div class="rounded-2xl border bg-white p-5">
  <h1 class="text-xl font-semibold mb-4">Créer un sujet</h1>
  <form action="{{ route('admin.subjects.store') }}" method="POST">
    @include('admin.subjects._form')
  </form>
</div>
@endsection
