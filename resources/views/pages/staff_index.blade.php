@extends('layouts.app')
@section('title','Daftar Staff')

@section('content')
<div class="produk-wrap" data-type="staff">
  <section class="draft">
    <h1 class="page-title">Daftar Staff</h1>
    <p class="page-sub">Total: {{ $staffs->count() }} Staff</p>
  </section>

  <section class="two-col">
    {{-- LEFT: TABLE --}}
    <div class="col left">
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th style="width:120px">NIK</th>
              <th>Nama</th>
              <th style="width:80px">Sex</th>
              <th style="width:150px">Location</th>
              <th style="width:150px">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($staffs as $staff)
              <tr>
                <td>{{ $staff->nik }}</td>
                <td>{{ $staff->name }}</td>
                <td>{{ $staff->sex }}</td>
                <td>{{ $staff->location }}</td>
                <td>
                  <a href="{{ route('staff.edit', $staff->nik) }}" class="btn btn-sm">Edit</a>
                  <form action="{{ route('staff.destroy', $staff->nik) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                      onclick="return confirm('Yakin ingin menghapus staff ini?')">Hapus</button>
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

        <form action="{{ route('staff.store') }}" method="POST">
          @csrf
          <div class="form-row">
            <label>NIK</label>
            <input type="text" name="nik" class="input" placeholder="Masukkan NIK" value="{{ old('nik') }}" required>
          </div>
          <div class="form-row">
            <label>Nama</label>
            <input type="text" name="name" class="input" placeholder="Nama Staff" value="{{ old('name') }}" required>
          </div>
          <div class="form-row">
            <label>Sex</label>
            <select name="sex" class="input">
              <option value="">Pilih</option>
              <option value="M" {{ old('sex')=='M' ? 'selected' : '' }}>M</option>
              <option value="F" {{ old('sex')=='F' ? 'selected' : '' }}>F</option>
            </select>
          </div>
          <div class="form-row">
            <label>Location</label>
            <input type="text" name="location" class="input" placeholder="Lokasi" value="{{ old('location') }}">
          </div>
          <button type="submit" class="btn btn-primary">Input Staff</button>
        </form>
      </div>
    </div>
  </section>
</div>
@endsection
