@extends('layouts.admin-dark')
@section('title','Modifier sujet')
@section('content')
@include('admin.partials.flash')
<div class="rounded-2xl border bg-white p-5">
  <h1 class="text-xl font-semibold mb-4">Modifier le sujet</h1>
  <form action="{{ route('admin.subjects.update',$subject) }}" method="POST">
    @method('PUT')
    @include('admin.subjects._form')
  </form>
</div>
@endsection
