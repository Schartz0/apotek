<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Login')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-auth">
  <main class="container-auth">
    @yield('content')
  </main>
</body>
</html>
