@extends('layouts.app')
@section('title','List Transaksi')

@section('content')
<section class="draft">
  <h1 class="page-title">List Transaksi</h1>
  <p class="page-sub">Daftar transaksi milik <strong>Admin 1</strong>.</p>
</section>

<section class="summary">
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th style="width: 120px;">ID</th>
          <th>Tanggal</th>
          <th>Created By</th>
          <th>Client</th>
          <th class="tar">Total</th>
          <th style="width: 160px;">Aksi</th>
        </tr>
      </thead>
      <tbody id="list-body">
        <tr><td colspan="6" class="muted">Belum ada transaksi.</td></tr>
      </tbody>
    </table>
  </div>
</section>
@endsection
