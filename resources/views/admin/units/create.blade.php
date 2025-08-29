@extends('layouts.admin-dark')
@section('title','Nouvelle UE')
@section('content')
@include('admin.partials.flash')
<div class="rounded-2xl border bg-white p-5">
  <h1 class="text-xl font-semibold mb-4">Créer une UE</h1>
  <form action="{{ route('admin.units.store') }}" method="POST">
    @include('admin.units._form')
  </form>
</div>
@endsection
