@extends('layouts.admin-dark')
@section('title','Utilisateurs')

@section('content')
  {{-- Flash messages --}}
  @if(session('ok'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('ok') }}</div>
  @endif
  @if(session('error'))
    <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">{{ session('error') }}</div>
  @endif

  <div class="mb-4 flex items-center justify-between">
    <form method="GET" class="flex items-center gap-2">
      <input type="text" name="q" value="{{ $q }}" placeholder="Rechercher nom ou email…"
             class="w-72 rounded-lg border px-3 py-2">
      <button class="rounded-lg bg-slate-900 px-3 py-2 text-white hover:bg-slate-800">Rechercher</button>
    </form>
    <div class="text-sm text-slate-600">Admins actuels : <strong>{{ $adminsCount }}</strong></div>
  </div>

  <div class="overflow-x-auto rounded-2xl border bg-white">
    <table class="w-full text-sm">
      <thead class="bg-slate-50">
        <tr class="text-left">
          <th class="px-4 py-2">Nom</th>
          <th class="px-4 py-2">Email</th>
          <th class="px-4 py-2">Rôle</th>
          <th class="px-4 py-2">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
          <tr class="border-t">
            <td class="px-4 py-2">{{ $u->name }}</td>
            <td class="px-4 py-2 text-slate-600">{{ $u->email }}</td>
            <td class="px-4 py-2">
              <span class="inline-flex rounded-full px-2 py-0.5 text-xs
                {{ $u->role==='admin' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700' }}">
                {{ $u->role }}
              </span>
            </td>
            <td class="px-4 py-2">
              @if($u->role !== 'admin')
                <form method="POST" action="{{ route('admin.users.updateRole',$u) }}" class="inline">
                  @csrf @method('PATCH')
                  <input type="hidden" name="role" value="admin">
                  <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-white hover:bg-emerald-500">
                    Promouvoir admin
                  </button>
                </form>
              @else
                <form method="POST" action="{{ route('admin.users.updateRole',$u) }}" class="inline">
                  @csrf @method('PATCH')
                  <input type="hidden" name="role" value="student">
                  <button class="rounded-lg bg-amber-600 px-3 py-1.5 text-white hover:bg-amber-500">
                    Rétrograder étudiant
                  </button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="px-4 py-6 text-center text-slate-500">Aucun utilisateur.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $users->links() }}</div>
@endsection
