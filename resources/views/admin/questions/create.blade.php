@extends('layouts.admin-dark')
@section('title','Nouvelle question')
@section('content')
@include('admin.partials.flash')
<div class="rounded-2xl border bg-white p-5">
  <h1 class="text-xl font-semibold mb-4">Créer une question</h1>
  <form action="{{ route('admin.questions.store') }}" method="POST">
    @include('admin.questions._form')
  </form>
</div>
@endsection
