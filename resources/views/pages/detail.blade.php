@extends('layouts.app')
@section('title','Detail Transaksi')

@section('content')
<section class="draft">
  <h1 class="page-title">Detail Transaksi</h1>
  <p class="page-sub">
    Ringkasan transaksi <span id="det-id">#—</span> •
    <span id="det-date">—</span> •
    Client: <strong id="det-client">—</strong> •
    Created by: <strong id="det-by">Admin 1</strong>
  </p>
</section>

<section class="summary" id="detail-root" style="display:none;">
  {{-- Service --}}
  <div class="table-wrap" id="box-service" style="margin-bottom:16px;">
    <div class="table-title">Service</div>
    <table class="table">
      <thead>
        <tr>
          <th>Ref #</th>
          <th>Created Date</th>
          <th>Created By</th>
          <th>Client</th>
          <th>Service</th>
          <th>Scheduled</th>
          <th>Time</th>
          <th>Duration</th>
          <th>Staff</th>
          <th class="tar">Price</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="tbody-service">
        <tr><td colspan="11" class="muted">Tidak ada service.</td></tr>
      </tbody>
    </table>
  </div>

  {{-- Obat --}}
  <div class="table-wrap" id="box-obat">
    <div class="table-title">Obat</div>
    <table class="table">
      <thead>
        <tr>
          <th>Created Date</th>
          <th>Created By</th>
          <th>Client</th>
          <th>Obat</th>
          <th>Quantity</th>
          <th class="tar">Price</th>
        </tr>
      </thead>
      <tbody id="tbody-obat">
        <tr><td colspan="6" class="muted">Tidak ada obat.</td></tr>
      </tbody>
    </table>
  </div>

  <div class="actions">
    <a href="/list" class="btn">Kembali</a>
    <div style="flex:1"></div>
    <div class="strong">Total : <span id="det-total">Rp. 0</span></div>
  </div>
</section>

<section id="notfound" class="draft" style="display:none;">
  <p class="muted">Transaksi tidak ditemukan.</p>
  <a href="/list" class="btn">Kembali ke List</a>
</section>
@endsection
