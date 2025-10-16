@extends('layouts.app')
@section('title','List Transaksi')

@section('content')
<section class="draft">
  <h1 class="page-title">List Transaksi</h1>
  <p class="page-sub">Daftar transaksi milik <strong>{{ auth()->user()->username ?? 'Admin' }}</strong>.</p>
</section>

<section class="summary">
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th style="width: 160px;">ID (Ref No)</th>
          <th>Tanggal</th>
          <th>Created By</th>
          <th>Client</th>
          <th class="tar">Total</th>
          <th style="width: 160px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
  @forelse($rows as $t)
    <tr>
      <td>{{ $t->ref_no }}</td>
      <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y H:i') }}</td>
      <td>{{ $t->created_by }}</td>
      <td>{{ $t->client_name }}</td>
      <td class="tar">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
      <td>
        <a href="{{ route('transactions.detail', $t->ref_no) }}" class="btn btn-sm btn-primary">Detail</a>
        <form action="{{ route('transactions.destroyByRef', $t->ref_no) }}" method="POST" style="display:inline"
              onsubmit="return confirm('Hapus transaksi {{ $t->ref_no }} ({{ $t->items_count }} item)? Tindakan ini tidak bisa dibatalkan.');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="6" class="muted">Belum ada transaksi.</td></tr>
  @endforelse
</tbody>

    </table>
  </div>
</section>

<style>
  .tar{text-align:right}
  .muted{color:#888}
</style>
@endsection
