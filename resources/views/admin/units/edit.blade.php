@extends('layouts.admin-dark')
@section('title','Modifier UE')
@section('content')
@include('admin.partials.flash')
<div class="rounded-2xl border bg-white p-5">
  <h1 class="text-xl font-semibold mb-4">Modifier l’UE</h1>
  <form action="{{ route('admin.units.update',$unit) }}" method="POST">
    @method('PUT')
    @include('admin.units._form')
  </form>
</div>
@endsection
