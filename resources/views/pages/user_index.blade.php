@extends('layouts.app')
@section('title','User Management')

@section('content')
<div class="produk-wrap">
    <section class="draft">
        <h1 class="page-title">User Management</h1>
        <p class="page-sub">Total: {{ $users->count() }} User</p>
    </section>

    <section class="two-col">
        {{-- LEFT: TABLE --}}
        <div class="col left">
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm">Edit</a>
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="muted">Belum ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT: FORM ADD --}}
        <div class="col right">
            <div class="card">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <label>Nama</label>
                        <input type="text" name="name" class="input" placeholder="Nama User" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-row">
                        <label>Username</label>
                        <input type="text" name="username" class="input" placeholder="Username" value="{{ old('username') }}" required>
                    </div>
                    <div class="form-row">
                        <label>Password</label>
                        <input type="password" name="password" class="input" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah User</button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
