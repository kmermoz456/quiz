{{-- Affichage des messages flash + erreurs de validation --}}
<div class="space-y-3">

  {{-- Succès (ex. with('ok', '…')) --}}
  @if (session('ok'))
    <div x-data="{show:true}" x-init="setTimeout(()=>show=false, 4000)" x-show="show"
         class="rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3">
      {{ session('ok') }}
    </div>
  @endif

  {{-- Info (ex. with('status', '…') utilisé par Breeze) --}}
  @if (session('status'))
    <div x-data="{show:true}" x-init="setTimeout(()=>show=false, 4000)" x-show="show"
         class="rounded-lg border border-sky-200 bg-sky-50 text-sky-800 px-4 py-3">
      {{ session('status') }}
    </div>
  @endif

  {{-- Alerte / erreur manuelle (ex. with('error','…') ou with('danger','…')) --}}
  @if (session('error') || session('danger'))
    <div class="rounded-lg border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3">
      {{ session('error') ?? session('danger') }}
    </div>
  @endif

  {{-- Warning éventuel --}}
  @if (session('warning'))
    <div class="rounded-lg border border-amber-200 bg-amber-50 text-amber-800 px-4 py-3">
      {{ session('warning') }}
    </div>
  @endif

  {{-- Erreurs de validation --}}
  @if ($errors->any())
    <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
      <div class="font-semibold mb-1">Veuillez corriger les champs :</div>
      <ul class="list-disc pl-5 space-y-0.5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

</div>
