@extends('layouts.app')
@section('title', 'Daftar Service')

@section('content')
<div class="produk-wrap" data-type="service">
  <section class="draft">
    <h1 class="page-title">Daftar Service</h1>
    <p class="page-sub">Total: {{ $services->count() }} Service</p>
  </section>

  <section class="two-col">
    {{-- LEFT: TABLE --}}
    <div class="col left">
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th style="width:60px">ID</th>
              <th>Nama Service</th>
              <th>Durasi</th>
              <th>Harga</th>
              <th style="width:100px">Tersedia</th>
              <th style="width:150px">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($services as $service)
              <tr>
                <td>{{ $service->id }}</td>
                <td>{{ $service->name }}</td>
                <td>{{ $service->duration ?? '-' }}</td>
                <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                <td>
                  @if($service->available)
                    <span class="badge bg-success">Ya</span>
                  @else
                    <span class="badge bg-danger">Tidak</span>
                  @endif
                </td>
                <td>
                  {{-- Tombol Edit --}}
                  <a href="{{ route('service.edit', $service->id) }}" class="btn btn-sm">Edit</a>

                  {{-- Form Hapus --}}
                  <form action="{{ route('service.destroy', $service->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                      onclick="return confirm('Yakin ingin menghapus service ini?')">Hapus</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="muted">Belum ada data service.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- RIGHT: FORM ADD --}}
    <div class="col right">
      <div class="card">
        <form action="{{ route('service.store') }}" method="POST">
          @csrf
          <div class="form-row">
            <label>Nama Service</label>
            <input type="text" name="name" class="input" placeholder="Masukkan nama service" required>
          </div>

          <div class="form-row">
            <label>Durasi</label>
            <input type="text" name="duration" class="input" placeholder="Contoh: 30 menit / 1 jam">
          </div>

          <div class="form-row">
            <label>Harga (Rp)</label>
            <input type="number" name="price" class="input" placeholder="Masukkan harga" required>
          </div>

          <div class="form-row">
            <label>Ketersediaan</label>
            <select name="available" class="input" required>
              <option value="" disabled selected>Pilih status</option>
              <option value="1">Tersedia</option>
              <option value="0">Tidak Tersedia</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Tambah Service</button>
        </form>
      </div>
    </div>
  </section>
</div>
@endsection
