@csrf
<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm">UE</label>
    <select name="unit_id" class="mt-1 w-full rounded-lg border px-3 py-2" required>
      <option value="">— Choisir —</option>
      @foreach($units as $u)
        <option value="{{ $u->id }}" @selected(old('unit_id',$subject->unit_id ?? '')==$u->id)>{{ $u->code }} — {{ $u->title }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm">Niveau</label>
    <select name="level" class="mt-1 w-full rounded-lg border px-3 py-2" required>
      @foreach(['L1','L2'] as $lvl)
        <option @selected(old('level',$subject->level ?? '')==$lvl)>{{ $lvl }}</option>
      @endforeach
    </select>
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm">Titre</label>
    <input type="text" name="title" value="{{ old('title',$subject->title ?? '') }}" class="mt-1 w-full rounded-lg border px-3 py-2" required>
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm">Description</label>
    <textarea name="description" rows="4" class="mt-1 w-full rounded-lg border px-3 py-2">{{ old('description',$subject->description ?? '') }}</textarea>
  </div>
</div>

<div class="mt-4 flex items-center gap-2">
  <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">Enregistrer</button>
  <a href="{{ route('admin.subjects.index') }}" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Annuler</a>
</div>
