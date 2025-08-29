@extends('layouts.admin-dark')
@section('title','Étudiants')
@section('content')
  <h1 class="text-xl font-semibold mb-4">Étudiants</h1>
  <div class="bg-white rounded-xl border p-4">
    <table class="w-full text-sm">
      <thead><tr><th>Nom</th><th>Email</th><th>Niveau</th></tr></thead>
      <tbody>
        @foreach($students as $u)
          <tr class="border-t">
            <td class="py-2">{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->level ?? '—' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="mt-3">{{ $students->links() }}</div>
  </div>
@endsection
