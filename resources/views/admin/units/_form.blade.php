@csrf
<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm">Code</label>
    <input type="text" name="code" value="{{ old('code',$unit->code ?? '') }}" class="mt-1 w-full rounded-lg border px-3 py-2" required>
  </div>
  <div>
    <label class="block text-sm">Intitulé</label>
    <input type="text" name="name" value="{{ old('name',$unit->name ?? '') }}" class="mt-1 w-full rounded-lg border px-3 py-2" required>
  </div>
</div>
<div class="mb-4">
  <label class="block text-sm font-medium text-slate-700">Niveau</label>
  <select name="level" class="mt-1 w-full rounded-lg border border-slate-300">
    <option value="L1" {{ old('level',$unit->level ?? '')=='L1'?'selected':'' }}>Licence 1</option>
    <option value="L2" {{ old('level',$unit->level ?? '')=='L2'?'selected':'' }}>Licence 2</option>
  </select>
</div>

<div class="mt-4 flex items-center gap-2">
  <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">Enregistrer</button>
  <a href="{{ route('admin.units.index') }}" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Annuler</a>
</div>

