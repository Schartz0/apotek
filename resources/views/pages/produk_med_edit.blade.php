@extends('layouts.app')
@section('title','Edit Obat')

@section('content')
<div class="produk-wrap">
  <h1 class="page-title">Edit Obat</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul style="margin:0; padding-left:18px;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('med.update', $med->id) }}" method="POST" class="card" style="max-width:500px">
    @csrf
    @method('PUT')

    <div class="form-row">
      <label>Nama Obat</label>
      <input type="text" name="name" class="input" value="{{ old('name', $med->name) }}" required>
    </div>

    <div class="form-row">
      <label>Stok</label>
      <input type="number" name="stock" class="input" value="{{ old('stock', $med->stock) }}" min="0" required>
    </div>

    <div class="form-row">
      <label>Harga</label>
      <input type="number" name="price" class="input" value="{{ old('price', $med->price) }}" min="0" required>
    </div>

    <div class="form-row" style="margin-top:12px;">
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="{{ route('med.index') }}" class="btn">Batal</a>
    </div>
  </form>
</div>
@endsection
