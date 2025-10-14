@extends('layouts.app')
@section('title','Edit Service')

@section('content')
<div class="produk-wrap">
  <h1 class="page-title">Edit Service</h1>

  <form action="{{ route('service.update', $service->id) }}" method="POST" class="card" style="max-width:500px">
    @csrf
    @method('PUT')

    <div class="form-row">
      <label>Nama Service</label>
      <input type="text" name="nama" class="input" value="{{ old('nama', $service->nama) }}" required>
    </div>

    <div class="form-row">
      <label>Stok</label>
      <select name="stok" class="input" required>
        <option value="Ada" {{ $service->stok == 'Ada' ? 'selected' : '' }}>Ada</option>
        <option value="Kosong" {{ $service->stok == 'Kosong' ? 'selected' : '' }}>Kosong</option>
      </select>
    </div>

    <div class="form-row">
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="{{ route('service.index') }}" class="btn">Batal</a>
    </div>
  </form>
</div>
@endsection
