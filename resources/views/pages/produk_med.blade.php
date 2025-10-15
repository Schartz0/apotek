@extends('layouts.app')
@section('title','Daftar Obat')

@section('content')
<div class="produk-wrap" data-type="med">
  <section class="draft">
    <h1 class="page-title">Daftar Obat</h1>
    <p class="page-sub">Total: {{ $meds->count() }} Obat</p>
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
              <th style="width:120px">Harga</th>
              <th style="width:150px">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($meds as $med)
              <tr>
                <td>{{ $med->id }}</td>
                <td>{{ $med->name }}</td>
                <td>{{ $med->stock }}</td>
                <td>Rp{{ number_format($med->price, 0, ',', '.') }}</td>
                <td>
                  <a href="{{ route('med.edit', $med->id) }}" class="btn btn-sm">Edit</a>
                  <form action="{{ route('med.destroy', $med->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                      onclick="return confirm('Yakin ingin menghapus obat ini?')">Hapus</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="muted">Belum ada data.</td></tr>
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

        <form action="{{ route('med.store') }}" method="POST">
          @csrf
          <div class="form-row">
            <label>Nama Obat</label>
            <input type="text" name="name" class="input" placeholder="Masukkan Nama Obat" value="{{ old('name') }}" required>
          </div>
          <div class="form-row">
            <label>Stok</label>
            <input type="number" name="stock" class="input" placeholder="Jumlah Stok" value="{{ old('stock') }}" min="0" required>
          </div>
          <div class="form-row">
            <label>Harga</label>
            <input type="number" name="price" class="input" placeholder="Harga Obat" value="{{ old('price') }}" min="0" required>
          </div>
          <button type="submit" class="btn btn-primary">Input Produk</button>
        </form>
      </div>
    </div>
  </section>
</div>
@endsection
