@extends('layouts.app')
@section('title', 'Edit Service')

@section('content')
<div class="produk-wrap" data-type="service">
  <section class="draft">
    <h1 class="page-title">Edit Service</h1>
    <p class="page-sub">Ubah data service berikut kemudian simpan perubahannya.</p>
  </section>

  <section class="one-col">
    <div class="card">
      <form action="{{ route('service.update', $service->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
          <label>Nama Service</label>
          <input type="text" name="name" class="input" value="{{ old('name', $service->name) }}" required>
        </div>

        <div class="form-row">
          <label>Durasi</label>
          <input type="text" name="duration" class="input" value="{{ old('duration', $service->duration) }}" placeholder="Contoh: 30 menit / 1 jam">
        </div>

        <div class="form-row">
          <label>Harga (Rp)</label>
          <input type="number" name="price" class="input" value="{{ old('price', $service->price) }}" required>
        </div>

        <div class="form-row">
          <label>Ketersediaan</label>
          <select name="available" class="input" required>
            <option value="1" {{ old('available', $service->available) ? 'selected' : '' }}>Tersedia</option>
            <option value="0" {{ !old('available', $service->available) ? 'selected' : '' }}>Tidak Tersedia</option>
          </select>
        </div>

        <div class="form-action">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          <a href="{{ route('service.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </section>
</div>
@endsection
