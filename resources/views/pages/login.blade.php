@extends('layouts.guest')
@section('title','Login')

@section('content')
  <section class="card-auth">
    <div class="auth-head">
      <div class="auth-logo"></div>
      <h1 class="auth-title">Login</h1>
      <p class="auth-sub">Silakan login untuk melanjutkan</p>
    </div>

    <form class="form-auth" autocomplete="off">
      <div class="form-row">
        <label for="username">Username</label>
        <input id="username" class="input" type="text" placeholder="Masukkan username">
      </div>

      <div class="form-row">
        <label for="password">Password</label>
        <input id="password" class="input" type="password" placeholder="Masukkan password">
      </div>

      <button type="button" class="btn btn-primary btn-login">Login</button>
      <!-- Belum ada aksi apa-apa. Nanti di langkah berikut kita buatkan dummy redirect ke /draft. -->
    </form>
  </section>
@endsection
