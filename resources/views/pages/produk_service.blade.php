@extends('layouts.app')
@section('title','Daftar Service')

@section('content')
<div class="produk-wrap" data-type="service">
  <section class="draft">
    <h1 class="page-title">Daftar Service</h1>
    <p class="page-sub">Total: <span class="js-total">0</span> Service</p>
  </section>

  <section class="two-col">
    {{-- LEFT: TABLE --}}
    <div class="col left">
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th style="width:80px">ID</th>
              <th>Nama Service</th>
              <th style="width:120px">Stok</th>
              <th style="width:150px">Action</th>
            </tr>
          </thead>
          <tbody class="js-tbody">
            <tr><td colspan="4" class="muted">Belum ada data.</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- RIGHT: FORM ADD --}}
    <div class="col right">
      <div class="card">
        <div class="form-row">
          <label>Nama Service</label>
          <input type="text" class="input js-name" placeholder="Masukkan Nama Service">
        </div>
        <div class="form-row">
          <label>Stok</label>
          <select class="input js-stock">
            <option value="" selected disabled>Stok</option>
            <option value="Ada">Ada</option>
            <option value="Kosong">Kosong</option>
          </select>
        </div>
        <button class="btn btn-primary js-add" disabled>Input Produk</button>
      </div>
    </div>
  </section>

  {{-- MODAL EDIT --}}
  <div class="modal" id="modal-edit" aria-hidden="true">
    <div class="modal__overlay js-close"></div>
    <div class="modal__box">
      <h3 class="modal__title"><span class="js-edit-title">Edit</span></h3>
      <div class="form-row">
        <label>Nama</label>
        <input type="text" class="input js-edit-name" placeholder="Ubah Nama">
      </div>
      <div class="form-row">
        <label>Stok</label>
        <select class="input js-edit-stock">
          <option value="" disabled>Stok</option>
          <option value="Ada">Ada</option>
          <option value="Kosong">Kosong</option>
        </select>
      </div>
      <div class="modal__actions">
        <button class="btn js-close">Batal</button>
        <button class="btn btn-primary js-save" disabled>Ubah</button>
      </div>
    </div>
  </div>
</div>
@endsection
