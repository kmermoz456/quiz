@csrf

@php
    // Type initial
    $initialType = old('type', $question->type ?? 'single');

    // Choix initiaux
    $initialChoices = old('choices');
    if (!$initialChoices) {
        $initialChoices = isset($question)
            ? $question->choices->map(fn($c) => [
                'id'         => $c->id,
                'label'      => $c->label,
                'is_correct' => (bool) $c->is_correct,
            ])->values()->all()
            : [['label' => '', 'is_correct' => false]];
    }

    $alpineState = [
        'type'    => $initialType,
        'choices' => $initialChoices,
    ];
@endphp

<div x-data='@json($alpineState)' class="space-y-4">
  <div class="grid md:grid-cols-3 gap-4">
    <div>
      <label class="block text-sm">Sujet</label>
      <select name="subject_id" class="mt-1 w-full rounded-lg border px-3 py-2" required>
        <option value="">— Choisir —</option>
        @foreach($subjects as $s)
          <option value="{{ $s->id }}" @selected(old('subject_id',$question->subject_id ?? '')==$s->id)>
            {{ $s->title }}
          </option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm">Type</label>
      <select name="type" x-model="type" class="mt-1 w-full rounded-lg border px-3 py-2">
        @foreach(['single'=>'Choix unique','multiple'=>'Choix multiples','true_false'=>'Vrai/Faux','text'=>'Réponse texte'] as $val=>$lbl)
          <option value="{{ $val }}">{{ $lbl }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm">Points</label>
      <input type="number" min="0" step="1" name="points"
             value="{{ old('points',$question->points ?? 1) }}"
             class="mt-1 w-full rounded-lg border px-3 py-2">
    </div>
  </div>

  <div>
    <label class="block text-sm">Énoncé</label>
    <textarea name="statement" rows="4"
              class="mt-1 w-full rounded-lg border px-3 py-2" required>
      {{ old('statement',$question->statement ?? '') }}
    </textarea>
  </div>

  {{-- Choix (single/multiple/true_false) --}}
  <template x-if="type !== 'text'">
    <div class="rounded-lg border p-3">
      <div class="flex items-center justify-between mb-2">
        <h3 class="font-medium text-sm">Choix</h3>
        <button type="button" class="px-2 py-1 rounded bg-slate-100 hover:bg-slate-200"
                @click="choices.push({label:'',is_correct:false})">
          + Ajouter
        </button>
      </div>

      <template x-for="(c,idx) in choices" :key="idx">
        <div class="flex items-center gap-2 mb-2">
          <input type="checkbox" x-model="c.is_correct" class="h-4 w-4 text-emerald-600">
          <input type="text" class="flex-1 rounded-lg border px-3 py-2" x-model="c.label" placeholder="Libellé du choix…">
          <button type="button" class="px-2 py-1 rounded bg-rose-50 text-rose-700 hover:bg-rose-100"
                  @click="choices.splice(idx,1)">Supprimer</button>
        </div>
      </template>

      {{-- export JSON dans un input caché --}}
      <input type="hidden" name="choices_json" :value="JSON.stringify(choices)">
      <p class="text-xs text-slate-500">
        Coche les réponses correctes. Pour « Vrai/Faux », mets deux choix “Vrai” et “Faux”.
      </p>
    </div>
  </template>

  <div class="flex items-center gap-2">
    <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">Enregistrer</button>
    <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Annuler</a>
  </div>
</div>
