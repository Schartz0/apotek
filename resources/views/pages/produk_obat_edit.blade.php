@extends('layouts.app')
@section('title','Edit Obat')

@section('content')
<div class="produk-wrap">
  <h1 class="page-title">Edit Obat</h1>

  <form action="{{ route('obat.update', $obat->id) }}" method="POST" class="card" style="max-width:500px">
    @csrf
    @method('PUT')

    <div class="form-row">
      <label>Nama Obat</label>
      <input type="text" name="nama" class="input" value="{{ old('nama', $obat->nama) }}" required>
    </div>

    <div class="form-row">
      <label>Stok</label>
      <select name="stok" class="input" required>
        <option value="Ada" {{ $obat->stok == 'Ada' ? 'selected' : '' }}>Ada</option>
        <option value="Kosong" {{ $obat->stok == 'Kosong' ? 'selected' : '' }}>Kosong</option>
      </select>
    </div>

    <div class="form-row">
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="{{ route('obat.index') }}" class="btn">Batal</a>
    </div>
  </form>
</div>
@endsection
