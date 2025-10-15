@extends('layouts.app')
@section('title','Edit Staff')

@section('content')
<div class="produk-wrap">
  <h1 class="page-title">Edit Staff</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul style="margin:0; padding-left:18px;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('staff.update', $staff->nik) }}" method="POST" class="card" style="max-width:500px">
    @csrf
    @method('PUT')

    <div class="form-row">
      <label>NIK</label>
      <input type="text" name="nik" class="input" value="{{ old('nik', $staff->nik) }}" required>
    </div>

    <div class="form-row">
      <label>Nama</label>
      <input type="text" name="name" class="input" value="{{ old('name', $staff->name) }}" required>
    </div>

    <div class="form-row">
      <label>Sex</label>
      <select name="sex" class="input">
        <option value="">Pilih</option>
        <option value="M" {{ old('sex', $staff->sex)=='M' ? 'selected' : '' }}>M</option>
        <option value="F" {{ old('sex', $staff->sex)=='F' ? 'selected' : '' }}>F</option>
      </select>
    </div>

    <div class="form-row">
      <label>Location</label>
      <input type="text" name="location" class="input" value="{{ old('location', $staff->location) }}">
    </div>

    <div class="form-row" style="margin-top:12px;">
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="{{ route('staff.index') }}" class="btn">Batal</a>
    </div>
  </form>
</div>
@endsection
