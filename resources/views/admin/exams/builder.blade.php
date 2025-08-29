@extends('layouts.admin-dark')
@section('title', "Builder — {$exam->title}")

@section('content')

@include('partials.flash') {{-- pour les messages ok/err --}}

<div class="max-w-4xl">
  <div class="rounded-2xl bg-white border p-5">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-lg font-semibold">Ordre des questions</h2>
        <p class="text-sm text-slate-500">
          Fais glisser les questions pour définir l’ordre d’affichage pendant l’examen.
        </p>
      </div>
      <a href="{{ route('admin.exams.index') }}" class="text-sm underline">← Retour aux examens</a>
    </div>

    @if($questions->isEmpty())
      <div class="p-4 rounded-lg bg-slate-50 border text-slate-600">
        Aucune question rattachée à cet examen.  
        <a class="underline" href="{{ route('admin.exams.edit', $exam) }}">Ajouter des questions</a>.
      </div>
    @else
      <form id="order-form" method="POST" action="{{ route('admin.exams.builder.update', $exam) }}" class="space-y-4">
        @csrf

        {{-- liste triable --}}
        <ul id="sortable" class="divide-y rounded-lg border">
          @foreach($questions as $q)
            <li class="flex items-center gap-3 p-3 bg-white" data-id="{{ $q->id }}">
              <span class="cursor-grab select-none text-slate-400" title="Glisser">
                {{-- icône poignée --}}
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                  <circle cx="7" cy="5" r="1"/><circle cx="7" cy="9" r="1"/><circle cx="7" cy="13" r="1"/><circle cx="7" cy="17" r="1"/>
                  <circle cx="11" cy="5" r="1"/><circle cx="11" cy="9" r="1"/><circle cx="11" cy="13" r="1"/><circle cx="11" cy="17" r="1"/>
                </svg>
              </span>
              <div class="flex-1">
                <div class="text-sm font-medium">
                  {{ Str::limit($q->statement, 140) }}
                </div>
                <div class="text-xs text-slate-500 mt-0.5">
                  {{ strtoupper($q->type) }} •
                  {{ $q->points }} pt(s) •
                  [{{ $q->subject->unit->code ?? 'UE' }} - {{ $q->subject->title ?? 'Sujet' }}]
                </div>
              </div>
              {{-- retirer la question de cet examen --}}
              <form method="POST"
                    action="{{ route('admin.exams.builder.remove', [$exam,$q]) }}"
                    onsubmit="return confirm('Retirer cette question de l’examen ?')">
                @csrf @method('DELETE')
                <button class="px-2 py-1 rounded bg-rose-50 text-rose-600 border border-rose-200 text-xs">
                  Retirer
                </button>
              </form>
            </li>
          @endforeach
        </ul>

        {{-- le champ hidden qui embarque l’ordre --}}
        <input type="hidden" name="order[]" id="order-input">

        <div class="flex items-center justify-end gap-2">
          <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">
            Enregistrer l’ordre
          </button>
        </div>
      </form>
    @endif
  </div>
</div>

{{-- SortableJS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const list     = document.getElementById('sortable');
    const orderInp = document.getElementById('order-input');
    const form     = document.getElementById('order-form');

    if (!list) return;

    const getIds = () => Array.from(list.querySelectorAll('[data-id]')).map(li => li.dataset.id);

    // init valeur par défaut (ordre actuel)
    orderInp.value = JSON.stringify(getIds());

    new Sortable(list, {
      animation: 150,
      handle: '.cursor-grab',
      onSort() {
        orderInp.value = JSON.stringify(getIds());
      }
    });

    // Au submit, on transforme le JSON en tableau name="order[]"
    form.addEventListener('submit', (e) => {
      // remplace le hidden unique par plusieurs inputs pour la validation Laravel
      const ids = JSON.parse(orderInp.value || '[]');
      orderInp.remove();
      ids.forEach(id => {
        const i = document.createElement('input');
        i.type  = 'hidden';
        i.name  = 'order[]';
        i.value = id;
        form.appendChild(i);
      });
    });
  });
</script>
@endsection
