{{-- resources/views/welcome.blade.php --}}
<!doctype html>
<html lang="fr" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ITF-Évaluation — Accueil</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  {{-- Alpine pour le menu mobile --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="shortcut icon" href="{{asset('icon.png')}}" type="image/x-icon">
</head>
<body class="h-full bg-white text-slate-900" x-data="{open:false}">

  @include('partials.menu')

  
        @yield('content')
   


  @include('partials.footer')