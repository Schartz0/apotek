<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Aplikasi Transaksi')</title>
  @vite(['resources/css/app.css'])
</head>
<body class="bg-page">
  @include('partials.navbar')

  <main class="container-page">
    @yield('content')
  </main>
   <script>
document.addEventListener('DOMContentLoaded', () => {
  const dropdownButtons = document.querySelectorAll('[data-dd]');

  dropdownButtons.forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation();

      // Tutup semua dropdown lain
      document.querySelectorAll('.nav-dropdown.open').forEach(drop => {
        if (drop !== btn.parentElement) drop.classList.remove('open');
      });

      // Toggle dropdown yang diklik
      const parent = btn.parentElement;
      parent.classList.toggle('open');
    });
  });

  // Klik di luar dropdown → tutup semua
  document.addEventListener('click', () => {
    document.querySelectorAll('.nav-dropdown.open').forEach(drop => {
      drop.classList.remove('open');
    });
  });
});
</script>

</body>
</html>
