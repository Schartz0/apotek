@extends('layouts.guest')
@section('title','Login')

@section('content')
<section class="card-auth">
  <div class="auth-head">
    <div class="auth-logo"></div>
    <h1 class="auth-title">Login</h1>
    <p class="auth-sub">Silakan login untuk melanjutkan</p>
  </div>

  <form class="form-auth" action="{{ route('login.process') }}" method="POST" autocomplete="off">
    @csrf

    <div class="form-row">
      <label for="username">Username</label>
      <input id="username" name="username" class="input" type="text" placeholder="Masukkan username" required>
    </div>

    <div class="form-row">
      <label for="password">Password</label>
      <input id="password" name="password" class="input" type="password" placeholder="Masukkan password" required>
    </div>

    {{-- Error Message --}}
    @if(session('error'))
      <p style="color: red; margin-top: 10px;">{{ session('error') }}</p>
    @endif


    <button type="submit" class="btn btn-primary btn-login">Login</button>
  </form>
</section>
@endsection
