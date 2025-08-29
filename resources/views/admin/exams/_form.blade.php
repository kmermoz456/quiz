@csrf
<div class="grid md:grid-cols-2 gap-4">
  <div class="md:col-span-2">
    <label class="block text-sm">Titre</label>
    <input type="text" name="title" value="{{ old('title',$exam->title ?? '') }}" class="mt-1 w-full rounded-lg border px-3 py-2" required>
  </div>
  <div>
    <label class="block text-sm">Durée (minutes)</label>
    <input type="number" name="duration_minutes" min="1" step="1" value="{{ old('duration_minutes',$exam->duration_minutes ?? 30) }}" class="mt-1 w-full rounded-lg border px-3 py-2" required>
  </div>
  <div>
    <label class="block text-sm">Publié ?</label>
    <select name="is_published" class="mt-1 w-full rounded-lg border px-3 py-2">
      <option value="0" @selected(old('is_published',$exam->is_published ?? 0)==0)>Non</option>
      <option value="1" @selected(old('is_published',$exam->is_published ?? 0)==1)>Oui</option>
    </select>
  </div>
  <div>
    <label class="block text-sm">Début (optionnel)</label>
    <input type="datetime-local" name="starts_at"
           value="{{ old('starts_at', isset($exam->starts_at) ? $exam->starts_at->format('Y-m-d\TH:i') : '') }}"
           class="mt-1 w-full rounded-lg border px-3 py-2">
  </div>
  <div>
    <label class="block text-sm">Fin (optionnel)</label>
    <input type="datetime-local" name="ends_at"
           value="{{ old('ends_at', isset($exam->ends_at) ? $exam->ends_at->format('Y-m-d\TH:i') : '') }}"
           class="mt-1 w-full rounded-lg border px-3 py-2">
  </div>
</div>
{{-- Sélection des questions --}}
<div class="mt-4">
  <label class="block text-sm font-medium">Questions de l’examen</label>
  <select name="question_ids[]" multiple
          class="mt-1 w-full rounded-lg border px-3 py-2 h-56">
    @php
      $selected = old('question_ids', isset($exam) ? $exam->questions->pluck('id')->toArray() : []);
    @endphp

    @foreach($questions as $q)
      <option value="{{ $q->id }}" @selected(in_array($q->id, $selected))>
        [{{ $q->subject->unit->code ?? 'UE' }} - {{ $q->subject->title ?? 'Sujet' }}]
        — {{ Str::limit($q->statement, 90) }}
      </option>
    @endforeach
  </select>
  <p class="text-xs text-slate-500 mt-1">
    Maintiens Ctrl (Windows) / Cmd (Mac) pour sélectionner plusieurs questions.
  </p>
</div>


<div class="mt-4 flex items-center gap-2">
  <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">Enregistrer</button>
  <a href="{{ route('admin.exams.index') }}" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Annuler</a>
</div>
