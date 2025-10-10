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
</body>
</html>
