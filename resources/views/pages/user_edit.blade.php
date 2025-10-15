@extends('layouts.app')
@section('title','Edit User')

@section('content')
<div class="produk-wrap">
    <section class="draft">
        <h1 class="page-title">Edit User</h1>
        <p class="page-sub">Ubah data user terdaftar</p>
    </section>

    <section class="two-col">
        <div class="col right">
            <div class="card">
                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Form edit user --}}
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <label>Nama</label>
                        <input type="text" name="name" class="input" value="{{ $user->name }}" required>
                    </div>

                    <div class="form-row">
                        <label>Username</label>
                        <input type="text" name="username" class="input" value="{{ $user->username }}" required>
                    </div>

                    <div class="form-row">
                        <label>Password (kosongkan jika tidak ingin diubah)</label>
                        <input type="password" name="password" class="input" placeholder="Password baru">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
