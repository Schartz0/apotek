@extends('layouts.app')
@section('title', 'Buat Transaksi')

@section('content')
<section class="buat-wrap">
  <div class="layout-34">
    {{-- ¾: FORM TRANSAKSI --}}
    <div class="col-transaksi">
      <div class="tab-bar">
        <div id="tab-container" class="tabs">
          <button class="tab active" data-tab="1">Transaksi 1</button>
        </div>
        <div class="tab-actions">
          <button class="btn btn-sm btn-success" id="btn-add-tab">+ Tambah Tab</button>
          <button class="btn btn-sm btn-danger" id="btn-remove-tab">− Hapus Tab</button>
        </div>
      </div>

      <div id="tab-contents">
        <div class="tab-content active" data-tab="1">
          <div class="transaksi-form">
            {{-- Client --}}
            <div class="grid-2">
              <div class="form-row"><label>Ref No</label><input type="text" class="input ref_no" readonly></div>
              <div class="form-row"><label>Created By</label><input type="text" class="input created_by" value="{{ auth()->user()->username ?? 'Admin' }}" readonly></div>
            </div>
            <div class="grid-2">
              <div class="form-row"><label>Nama Client</label><input type="text" class="input client_name" placeholder="Nama client"></div>
              <div class="form-row"><label>Jenis Kelamin</label><select class="input sex"><option value="">Pilih</option><option value="M">Pria</option><option value="F">Wanita</option></select></div>
            </div>
            <div class="grid-2">
              <div class="form-row"><label>Usia</label><input type="number" class="input age" min="0" max="120" placeholder="Usia"></div>
              <div class="form-row"><label>Pekerjaan</label><input type="text" class="input occupation" placeholder="Pekerjaan"></div>
            </div>

            <hr>

            {{-- Produk --}}
            <div class="produk-area">
              <div class="grid-2">
                <div class="form-row product-container">
                  <label>Nama Produk</label>
                  <input type="text" class="input product-search" placeholder="Ketik nama produk...">
                  <div class="dropdown-list"></div>
                  <input type="hidden" class="product_id">
                  <input type="hidden" class="product_type">
                </div>
                <div class="form-row"><label>Qty</label><input type="number" class="input qty" min="1" value="1"></div>
              </div>
              <div class="grid-2">
                <div class="form-row"><label>Price (Rp)</label><input type="number" class="input price" min="0" placeholder="Otomatis"></div>
                <div class="form-row"><label>Scheduled Date</label><input type="date" class="input scheduled_date"></div>
              </div>
              <div class="grid-2">
                <div class="form-row"><label>Scheduled Time</label><input type="time" class="input scheduled_time"></div>
                <div class="form-row"><label>Staff NIK</label><select class="input staff_nik"><option selected disabled>Pilih Staff</option>@foreach(\App\Models\Staff::all() as $s)<option value="{{ $s->nik }}" data-location="{{ $s->location }}">{{ $s->name }} ({{ $s->location }})</option>@endforeach</select></div>
              </div>
              <div class="grid-2">
                <div class="form-row"><label>Location</label><input type="text" class="input location" placeholder="Otomatis" readonly></div>
                <div class="form-row"><label>Status</label><select class="input status"><option value="NEW">NEW</option><option value="COMPLETED">COMPLETED</option></select></div>
              </div>
              <button type="button" class="btn btn-sm btn-primary btn-tambah-ke-ringkasan">Tambah ke Ringkasan</button>
            </div>

          </div>
        </div>
      </div>
    </div>

    {{-- ¼: REKOMENDASI --}}
    @include('partials.rekomendasi')
</section>

{{-- Ringkasan --}}
<section class="summary">
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Produk</th>
          <th>Qty</th>
          <th>Harga</th>
          <th>Subtotal</th>
          <th>Staff</th>
          <th>Lokasi</th>
          <th>Tanggal</th>
          <th>Jam</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="sum-body">
        <tr><td colspan="9" class="muted">Belum ada item.</td></tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="tar strong">Total :</td>
          <td class="tar strong" id="sum-total">Rp. 0</td>
          <td colspan="5"></td>
        </tr>
      </tfoot>
    </table>
  </div>
  <div class="actions">
    <a href="{{ route('draft.page') }}" class="btn btn-danger">Batalkan</a>
    <button class="btn btn-primary" id="btn-proses">Proses Transaksi</button>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', ()=>{
  let tabCount = 1;
  const tabs   = document.getElementById('tab-container');
  const contents = document.getElementById('tab-contents');
  const btnAdd   = document.getElementById('btn-add-tab');
  const btnRem   = document.getElementById('btn-remove-tab');

  function activateTab(tab){
    document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
    tab.classList.add('active');
    document.querySelector(`.tab-content[data-tab="${tab.dataset.tab}"]`).classList.add('active');
  }

  btnAdd.addEventListener('click', ()=>{
    tabCount++;
    const newTab = document.createElement('button');
    newTab.className = 'tab'; newTab.dataset.tab = tabCount;
    newTab.textContent = 'Transaksi '+tabCount;

    const newContent = document.createElement('div');
    newContent.className = 'tab-content'; newContent.dataset.tab = tabCount;
    newContent.innerHTML = document.querySelector('.tab-content[data-tab="1"]').innerHTML;

    tabs.appendChild(newTab); contents.appendChild(newContent);
    activateTab(newTab); initTabForm(newContent);
  });

  btnRem.addEventListener('click', ()=>{
    const active = document.querySelector('.tab.active');
    if(!active) return;
    const id = active.dataset.tab;
    const content = document.querySelector(`.tab-content[data-tab="${id}"]`);
    if(tabs.children.length>1){ active.remove(); content.remove(); activateTab(tabs.lastElementChild); }
    else alert('Minimal harus ada satu tab transaksi!');
  });

  tabs.addEventListener('click', e=>{ if(e.target.classList.contains('tab')) activateTab(e.target); });

  function initTabForm(wrapper){
    wrapper.querySelector('.ref_no').value = 'REF-'+Math.random().toString(36).substring(2,8).toUpperCase();

    wrapper.querySelector('.staff_nik').addEventListener('change', e=>{
      const opt = e.target.selectedOptions[0];
      wrapper.querySelector('.location').value = opt.dataset.location||'';
    });

    attachProductSearch(wrapper);
    wrapper.querySelector('.btn-tambah-ke-ringkasan').addEventListener('click', ()=> tambahKeRingkasan(wrapper));
  }

  function attachProductSearch(wrapper){
    const input  = wrapper.querySelector('.product-search');
    const container = wrapper.querySelector('.product-container');
    const list   = wrapper.querySelector('.dropdown-list');

    input.addEventListener('input', async e=>{
      const q = input.value.trim(); if(q.length<2){list.style.display='none'; return;}
      try{
        const res = await fetch(`/products/search?q=${encodeURIComponent(q)}`);
        const data = await res.json();
        list.innerHTML='';
        data.forEach(p=>{
          const opt=document.createElement('div'); opt.className='dropdown-option';
          opt.textContent=`${p.name} - Rp${p.price}`;
          opt.dataset.id=p.id; opt.dataset.type=p.type; opt.dataset.price=p.price;
          list.appendChild(opt);
        });
        list.style.display=data.length?'block':'none';
      }catch(err){console.error(err);}
    });

    list.addEventListener('click', e=>{
      if(e.target.classList.contains('dropdown-option')){
        input.value = e.target.textContent.split(' - ')[0];
        wrapper.querySelector('.product_id').value   = e.target.dataset.id;
        wrapper.querySelector('.product_type').value = e.target.dataset.type;
        wrapper.querySelector('.price').value        = e.target.dataset.price;
        list.style.display='none';
      }
    });

    document.addEventListener('click', e=>{
      if(!container.contains(e.target)) list.style.display='none';
    });
  }

 function tambahKeRingkasan(wrapper){
    const name  = wrapper.querySelector('.product-search').value.trim();
    const qty   = parseInt(wrapper.querySelector('.qty').value)||0;
    const price = parseInt(wrapper.querySelector('.price').value)||0;

    const staffSelect = wrapper.querySelector('.staff_nik');
    const staffNik = staffSelect.value; // ambil NIK
    const staffName = staffSelect.selectedOptions[0]?.text.split(' (')[0] || '-';
    const loc = staffSelect.selectedOptions[0]?.dataset.location || '';
    const date  = wrapper.querySelector('.scheduled_date').value;
    const time  = wrapper.querySelector('.scheduled_time').value;
    const sub   = qty*price;
    if(!name || !price){ alert('Lengkapi produk dan harga'); return; }

    const tbody = document.getElementById('sum-body');
    if(tbody.querySelector('.muted')) tbody.innerHTML='';

    const tr=document.createElement('tr');
    tr.innerHTML=`
      <td>${name}</td>
      <td>${qty}</td>
      <td>Rp. ${price.toLocaleString('id-ID')}</td>
      <td>Rp. ${sub.toLocaleString('id-ID')}</td>
      <td data-nik="${staffNik}">${staffName}</td>
      <td>${loc}</td>
      <td>${loc}</td>
      <td>${date}</td>
      <td>${time}</td>
      <td><button type="button" class="btn-hapus-produk btn btn-sm btn-outline-danger">Hapus</button></td>
    `;
    tbody.appendChild(tr);
    tr.querySelector('.btn-hapus-produk').addEventListener('click', ()=>{ tr.remove(); hitungTotal(); });
    hitungTotal();
  }

  function hitungTotal(){
    let tot=0;
    document.querySelectorAll('#sum-body tr').forEach(tr=>{
      const sub = parseInt(tr.cells[3].textContent.replace(/[^\d]/g,''))||0;
      tot+=sub;
    });
    document.getElementById('sum-total').textContent='Rp. '+tot.toLocaleString('id-ID');
  }

  initTabForm(document.querySelector('.tab-content.active'));
});

// =============== PROSES TRANSAKSI ===================
document.getElementById('btn-proses').addEventListener('click', async ()=>{

  // Ambil data client dari tab pertama (anggap satu klien per transaksi)
  const firstTab = document.querySelector('.tab-content[data-tab="1"]');
  const clientData = {
    client_name: firstTab.querySelector('.client_name').value.trim(),
    age: firstTab.querySelector('.age').value || null,
    occupation: firstTab.querySelector('.occupation').value.trim(),
    sex: firstTab.querySelector('.sex').value || null,
  };

  if(!clientData.client_name){
    alert('Nama client wajib diisi!');
    return;
  }

  // Ambil semua item dari ringkasan
  const items = [];
  document.querySelectorAll('#sum-body tr').forEach(tr=>{
    if(tr.querySelector('.muted')) return; // skip placeholder
    const tds = tr.querySelectorAll('td');
    items.push({
      product_name: tds[0].textContent.trim(),
      qty: parseInt(tds[1].textContent.replace(/\D/g,'')) || 1,
      price: parseFloat(tds[2].textContent.replace(/[^\d]/g,'')) || 0,
      product_id: 0, // nanti bisa diset otomatis saat search produk
      product_type: 'service', // bisa disesuaikan
      scheduled_date: tds[6].textContent.trim() || null,
      scheduled_time: tds[7].textContent.trim() || null,
      staff_nik: tds[4].dataset.nik,
      location: tds[5].textContent.trim(),
      status: 'NEW'
    });
  });

  if(items.length === 0){
    alert('Belum ada produk di ringkasan.');
    return;
  }

  // Kirim ke backend
  try{
    const res = await fetch("{{ route('transactions.store') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        ...clientData,
        items
      })
    });

    const data = await res.json();
    if(data.success){
      alert('Transaksi berhasil disimpan! Ref: '+data.ref_no);
      window.location.href = "{{ route('draft.page') }}";
    }else{
      alert('Gagal menyimpan transaksi.');
    }
  }catch(err){
    console.error(err);
    alert('Terjadi kesalahan server.');
  }
});

</script>

<style>
/* Layout 3/4 vs 1/4 */
.layout-34{display:flex; gap:20px; align-items:flex-start;}
.col-transaksi{flex:0 0 75%;}
.col-rekomendasi{flex:0 0 23%; background:#fafafa; padding:15px; border:1px solid #e5e5e5; border-radius:6px;}
.col-rekomendasi h5{margin-top:0;}

/* Tab */
.tab-bar{display:flex; justify-content:space-between; margin-bottom:15px;}
.tabs{display:flex; gap:6px;}
.tab-actions{display:flex; gap:6px;}
.tab{background:#f3f3f3; border:1px solid #ccc; padding:6px 12px; border-radius:4px; cursor:pointer;}
.tab.active{background:#007bff; color:white; border-color:#007bff;}
.tab-content{display:none;}
.tab-content.active{display:block;}

/* Produk */
.product-container{position:relative;}
.dropdown-list{position:absolute; top:100%; left:0; width:100%; max-height:150px; overflow-y:auto; background:#fff; border:1px solid #ccc; z-index:50; display:none;}
.dropdown-option{padding:6px 8px; cursor:pointer;}
.dropdown-option:hover{background:#e7f1ff;}

/* Summary */
.summary{margin-top:30px;}
</style>
@endsection