@extends('layouts.app')
@section('title','Daftar Obat')

@section('content')
<div class="produk-wrap" data-type="obat">
  <section class="draft">
    <h1 class="page-title">Daftar Obat</h1>
    <p class="page-sub">Total: <span class="js-total">0</span> Obat</p>
  </section>

  <section class="two-col">
    {{-- LEFT: TABLE --}}
    <div class="col left">
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th style="width:80px">ID</th>
              <th>Nama Obat</th>
              <th style="width:120px">Stok</th>
              <th style="width:150px">Action</th>
            </tr>
          </thead>
          <tbody class="js-tbody">
            <tr><td colspan="4" class="muted">Memuat data...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- RIGHT: FORM ADD --}}
    <div class="col right">
      <div class="card">
        <div class="form-row">
          <label>Nama Obat</label>
          <input type="text" class="input js-name" placeholder="Masukkan Nama Obat">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const tbody = document.querySelector('.js-tbody');
  const totalEl = document.querySelector('.js-total');
  const nameInput = document.querySelector('.js-name');
  const stockInput = document.querySelector('.js-stock');
  const addBtn = document.querySelector('.js-add');

  const modal = document.getElementById('modal-edit');
  const editName = modal.querySelector('.js-edit-name');
  const editStock = modal.querySelector('.js-edit-stock');
  const saveBtn = modal.querySelector('.js-save');

  let editId = null;

  // Fungsi bantu fetch dengan CSRF
  async function fetchJSON(url, options = {}) {
    const res = await fetch(url, {
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      ...options
    });
    return res.ok ? res.json() : [];
  }

  // 🔹 Load semua data obat
  async function loadData() {
    tbody.innerHTML = `<tr><td colspan="4" class="muted">Memuat...</td></tr>`;
    const data = await fetchJSON('/obat');
    tbody.innerHTML = '';
    if (!data.length) {
      tbody.innerHTML = `<tr><td colspan="4" class="muted">Belum ada data.</td></tr>`;
    } else {
      totalEl.textContent = data.length;
      data.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${item.id}</td>
          <td>${item.nama}</td>
          <td>${item.stok}</td>
          <td>
            <button class="btn btn-sm js-edit" data-id="${item.id}">Edit</button>
            <button class="btn btn-sm btn-danger js-del" data-id="${item.id}">Hapus</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }
  }

  // 🔹 Tambah data
  addBtn.addEventListener('click', async () => {
    const nama = nameInput.value.trim();
    const stok = stockInput.value;
    if (!nama || !stok) return;

    await fetchJSON('/obat', {
      method: 'POST',
      body: JSON.stringify({ nama, stok })
    });
    nameInput.value = '';
    stockInput.value = '';
    addBtn.disabled = true;
    await loadData();
  });

  // Aktifkan tombol tambah jika input terisi
  [nameInput, stockInput].forEach(el => {
    el.addEventListener('input', () => {
      addBtn.disabled = !(nameInput.value.trim() && stockInput.value);
    });
  });

  // 🔹 Aksi edit / hapus
  tbody.addEventListener('click', async (e) => {
    if (e.target.classList.contains('js-edit')) {
      editId = e.target.dataset.id;
      const data = await fetchJSON(`/obat/${editId}`);
      editName.value = data.nama;
      editStock.value = data.stok;
      saveBtn.disabled = true;
      modal.removeAttribute('aria-hidden');
    }

    if (e.target.classList.contains('js-del')) {
      const id = e.target.dataset.id;
      if (confirm('Yakin ingin menghapus obat ini?')) {
        await fetchJSON(`/obat/${id}`, { method: 'DELETE' });
        await loadData();
      }
    }
  });

  // 🔹 Simpan hasil edit
  saveBtn.addEventListener('click', async () => {
    await fetchJSON(`/obat/${editId}`, {
      method: 'PUT',
      body: JSON.stringify({
        nama: editName.value.trim(),
        stok: editStock.value
      })
    });
    modal.setAttribute('aria-hidden', 'true');
    await loadData();
  });

  [editName, editStock].forEach(el => {
    el.addEventListener('input', () => {
      saveBtn.disabled = !(editName.value.trim() && editStock.value);
    });
  });

  modal.querySelectorAll('.js-close').forEach(el => {
    el.addEventListener('click', () => {
      modal.setAttribute('aria-hidden', 'true');
    });
  });

  // Load data pertama kali
  loadData();
});
</script>
@endpush
