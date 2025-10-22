<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Aplikasi Transaksi')</title>

  {{-- Vite assets --}}
  @vite(['resources/css/app.css'])
  {{-- kalau kamu punya JS Vite global, aktifkan juga: --}}
  {{-- @vite(['resources/js/app.js']) --}}

  {{-- <- penting untuk partials: --}}
  @stack('styles')
  <style>
    /* kalau belum pakai Tailwind, pastikan util hidden ada */
    .hidden{display:none!important;}
  </style>
</head>
<body class="bg-page">
  @include('partials.navbar')

  <main class="container-page">
    @yield('content')
  </main>

  {{-- dropdown script kamu --}}
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const dropdownButtons = document.querySelectorAll('[data-dd]');
    dropdownButtons.forEach(btn => {
      btn.addEventListener('click', e => {
        e.stopPropagation();
        document.querySelectorAll('.nav-dropdown.open').forEach(drop => {
          if (drop !== btn.parentElement) drop.classList.remove('open');
        });
        const parent = btn.parentElement;
        parent.classList.toggle('open');
      });
    });
    document.addEventListener('click', () => {
      document.querySelectorAll('.nav-dropdown.open').forEach(drop => drop.classList.remove('open'));
    });
  });
  </script>

  {{-- <- penting untuk partials: --}}
  @stack('scripts')
</body>
</html>
