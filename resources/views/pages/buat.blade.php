@extends('layouts.app')
@section('title','Buat Transaksi')

@section('content')
<section class="buat-wrap">
  <!-- ========================= KIRI: FORM ========================= -->
  <div class="buat-left">

    <!-- Header Created By + Staff & Client -->
    <div class="section">
      <div class="created-by">
        <span>Created By</span>
        <strong>Admin 1</strong>
      </div>

      <div class="grid-2">
        <div class="form-row">
          <label>Nama Staff</label>
          <select class="input js-staff">
            <option selected disabled>Pilih Staff</option>
            <option>— dummy staff —</option>
          </select>
        </div>

        <div class="form-row">
          <label>Nama Client</label>
          <input type="text" class="input js-client" placeholder="Masukkan Nama Client">
        </div>
      </div>
    </div>

    <!-- ==================== DETAIL PESANAN (MULTI BLOK) ==================== -->
    <div class="section">
      <h3 class="subttl">Detail Pesanan</h3>

      <!-- container blok-blok -->
      <div class="orders" id="orders"></div>

      <!-- template sebuah blok (dikloning via JS) -->
      <template id="tpl-order">
        <div class="order-block" data-index="">
          <div class="order-head">
            <div class="form-row w-200">
              <label>Jenis</label>
              <select class="input js-jenis">
                <option selected disabled>Pilih</option>
                <option value="service">Service</option>
                <option value="obat">Obat</option>
              </select>
            </div>

            <div class="order-actions">
              <button class="btn-icon js-minus" title="Hapus blok">–</button>
              <button class="btn-icon js-plus"  title="Tambah blok">+</button>
            </div>
          </div>

          <div class="order-body">
            <!-- SERVICE -->
            <div class="js-fields-service hidden">
              <div class="grid-2">
                <div class="form-row">
                  <label>Nama Service</label>
                  <select class="input js-nama-service">
                    <option selected disabled>Pilih Service</option>
                    <option value="Skin Booster Korea" data-price="250000" data-duration="15">Skin Booster Korea</option>
                    <option value="Dermabration - Vacuum Pore" data-price="150000" data-duration="5">Dermabration - Vacuum Pore</option>
                    <option value="IPL Face Whitening / Acne" data-price="180000" data-duration="10">IPL Face Whitening / Acne</option>
                  </select>
                </div>

                <div class="form-row">
                  <label>Scheduled Date</label>
                  <input type="date" class="input js-date">
                </div>

                <div class="form-row">
                  <label>Time</label>
                  <input type="time" class="input js-time">
                </div>

                <div class="form-row">
                  <label>Duration (min)</label>
                  <input type="number" class="input js-duration" min="0" placeholder="15">
                </div>

                <div class="form-row">
                  <label>Price (Rp)</label>
                  <input type="number" class="input js-price" min="0" placeholder="250000">
                </div>

                <div class="form-row">
                  <label>Channel</label>
                  <select class="input js-channel">
                    <option selected disabled>Pilih Channel</option>
                    <option>Point Of Sale</option>
                  </select>
                </div>

                <div class="form-row">
                  <label>Status</label>
                  <select class="input js-status">
                    <option selected disabled>Pilih</option>
                    <option>New</option>
                    <option>Completed</option>
                  </select>
                </div>

                <div class="form-row">
                  <label>Referral #</label>
                  <input type="text" class="input js-ref" placeholder="HRWZ08JS">
                </div>
              </div>
            </div>

            <!-- OBAT -->
            <div class="js-fields-obat hidden">
              <div class="grid-2">
                <div class="form-row">
                  <label>Nama Obat</label>
                  <select class="input js-nama-obat">
                    <option selected disabled>Pilih Obat</option>
                    <option value="Phil Whitening" data-price="30000">Phil Whitening</option>
                    <option value="Brightening cream" data-price="55000">Brightening cream</option>
                  </select>
                </div>

                <div class="form-row">
                  <label>Quantity (pcs)</label>
                  <input type="number" class="input js-qty" min="1" placeholder="1">
                </div>

                <div class="form-row">
                  <label>Price (Rp)</label>
                  <input type="number" class="input js-price-obat" min="0" placeholder="30000">
                </div>

                <div class="form-row">
                  <label>Referral #</label>
                  <input type="text" class="input js-ref-obat" placeholder="HRWZ08JS">
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>

    </div><!-- /section Detail Pesanan -->
  </div><!-- /buat-left -->

  <!-- ========================= KANAN: REKOMENDASI ========================= -->
  <aside class="buat-right">
    <h3 class="subttl center">Rekomendasi</h3>
    <div class="reco-box">
      <div class="js-reco-head muted center">Pilih <em>Jenis</em> terlebih dahulu.</div>
      <div class="js-reco-list reco-list"></div>
    </div>
  </aside>
</section>

<!-- ========================= RINGKASAN + AKSI ========================= -->
<section class="summary">
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Jenis Pesanan</th>
          <th>Nama Pesanan</th>
          <th>Scheduled</th>
          <th>Qty./Dur.</th>
          <th class="tar">Subtotal</th>
        </tr>
      </thead>
      <tbody id="sum-body">
        <tr><td colspan="5" class="muted">Belum ada item.</td></tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" class="tar strong">Total :</td>
          <td class="tar strong" id="sum-total">Rp. 0</td>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="actions">
    <a href="/draft" class="btn btn-danger">Batalkan</a>
    <button class="btn btn-primary" id="btn-proses" disabled>Proses Transaksi</button>
  </div>
</section>
@endsection
