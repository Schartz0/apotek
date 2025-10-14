@extends('layouts.app')
@section('title','Daftar Obat')

@section('content')
<div class="produk-wrap" data-type="obat">
  <section class="draft">
    <h1 class="page-title">Daftar Obat</h1>
    <p class="page-sub">Total: {{ $obats->count() }} Obat</p>
  </section>

  <section class="two-col">
    {{-- LEFT: TABLE --}}
    <div class="col left">
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th style="width:80px">ID</th>
              <th>Nama Obat</th>
              <th style="width:120px">Stok</th>
              <th style="width:150px">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($obats as $obat)
              <tr>
                <td>{{ $obat->id }}</td>
                <td>{{ $obat->nama }}</td>
                <td>{{ $obat->stok }}</td>
                <td>
                  {{-- Tombol Edit --}}
                  <a href="{{ route('obat.edit', $obat->id) }}" class="btn btn-sm">Edit</a>

                  {{-- Form Hapus --}}
                  <form action="{{ route('obat.destroy', $obat->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                      onclick="return confirm('Yakin ingin menghapus obat ini?')">Hapus</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="muted">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- RIGHT: FORM ADD --}}
    <div class="col right">
      <div class="card">
        <form action="{{ route('obat.store') }}" method="POST">
          @csrf
          <div class="form-row">
            <label>Nama Obat</label>
            <input type="text" name="nama" class="input" placeholder="Masukkan Nama Obat" required>
          </div>
          <div class="form-row">
            <label>Stok</label>
            <select name="stok" class="input" required>
              <option value="" selected disabled>Stok</option>
              <option value="Ada">Ada</option>
              <option value="Kosong">Kosong</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Input Produk</button>
        </form>
      </div>
    </div>
  </section>
</div>
@endsection
