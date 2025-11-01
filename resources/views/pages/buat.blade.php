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
        {{-- TEMPLATE TAB #1 --}}
        <div class="tab-content active" data-tab="1">
          <div class="transaksi-form">
            {{-- Client --}}
            <div class="grid-2">
              <div class="form-row">
                <label>Ref No</label>
                <input type="text" class="input ref_no" placeholder="TX-XXXXXX (readonly)" readonly>
              </div>
              <div class="form-row">
                <label>Created By</label>
                <input type="text" class="input created_by" value="{{ auth()->user()->username ?? 'Admin' }}" readonly>
              </div>
            </div>
            <div class="grid-2">
              <div class="form-row">
                <label>Nama Client</label>
                <input type="text" class="input client_name" placeholder="Nama client">
              </div>
              <div class="form-row">
                <label>Jenis Kelamin</label>
                <select class="input sex">
                  <option value="">Pilih</option>
                  <option value="M">Pria</option>
                  <option value="F">Wanita</option>
                </select>
              </div>
            </div>
            <div class="grid-2">
              <div class="form-row">
                <label>Usia</label>
                <input type="number" class="input age" min="0" max="120" placeholder="Usia">
              </div>
              <div class="form-row">
                <label>Pekerjaan</label>
                <input type="text" class="input occupation" placeholder="Pekerjaan">
              </div>
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
                <div class="form-row">
                  <label>Qty</label>
                  <input type="number" class="input qty" min="1" value="1">
                </div>
              </div>
              <div class="grid-2">
                <div class="form-row">
                  <label>Price (Rupiah)</label>
                  <input type="number" class="input price" min="0" placeholder="Otomatis" readonly>
                </div>
                <div class="form-row">
                  <label>Scheduled Date</label>
                  <input type="date" class="input scheduled_date">
                </div>
              </div>
              <div class="grid-2">
                <div class="form-row">
                  <label>Scheduled Time</label>
                  <input type="time" class="input scheduled_time">
                </div>
                <div class="form-row">
                  <label>Staff NIK</label>
                  <select class="input staff_nik">
                    <option selected disabled>Pilih Staff</option>
                    @foreach(\App\Models\Staff::all() as $s)
                      <option value="{{ $s->nik }}" data-location="{{ $s->location }}">{{ $s->name }} ({{ $s->location }})</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="grid-2">
                <div class="form-row">
                  <label>Location</label>
                  <input type="text" class="input location" placeholder="Otomatis" readonly>
                </div>
                <div class="form-row">
                  <label>Status</label>
                  <select class="input status">
                    <option value="NEW">NEW</option>
                    <option value="COMPLETED">COMPLETED</option>
                  </select>
                </div>
              </div>
              <button type="button" class="btn btn-sm btn-primary btn-tambah-ke-ringkasan">Tambah ke Ringkasan</button>
            </div>

            {{-- RINGKASAN PER TAB --}}
            <section class="summary mt-3">
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
                  <tbody class="sum-body">
                    <tr><td colspan="9" class="muted">Belum ada item.</td></tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3" class="tar strong">Total :</td>
                      <td class="tar strong sum-total">Rp. 0</td>
                      <td colspan="5"></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </section>

          </div>
        </div>
      </div>
    </div>

    {{-- ¼: REKOMENDASI --}}
    @include('partials.rekomendasi')
  </div>
</section>

<div class="actions" style="margin-top:18px;">
  <a href="{{ route('draft.page') }}" class="btn btn-danger">Batalkan</a>
  <button class="btn btn-primary" id="btn-proses">Proses Transaksi</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', ()=>{
  let tabCount = 1;
  const tabs     = document.getElementById('tab-container');
  const contents = document.getElementById('tab-contents');
  const btnAdd   = document.getElementById('btn-add-tab');
  const btnRem   = document.getElementById('btn-remove-tab');

  function activateTab(tabBtn){
    document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
    tabBtn.classList.add('active');
    document.querySelector(`.tab-content[data-tab="${tabBtn.dataset.tab}"]`).classList.add('active');
  }

  btnAdd.addEventListener('click', ()=>{
    tabCount++;
    const newTab = document.createElement('button');
    newTab.className = 'tab';
    newTab.dataset.tab = tabCount;
    newTab.textContent = 'Transaksi ' + tabCount;

    const newContent = document.createElement('div');
    newContent.className = 'tab-content';
    newContent.dataset.tab = tabCount;
    // clone isi tab #1
    newContent.innerHTML = document.querySelector('.tab-content[data-tab="1"]').innerHTML;

    tabs.appendChild(newTab);
    contents.appendChild(newContent);

    // bersihkan ringkasan & input produk di tab baru
    const tbody = newContent.querySelector('.sum-body');
    tbody.innerHTML = '<tr><td colspan="9" class="muted">Belum ada item.</td></tr>';
    newContent.querySelector('.product-search').value = '';
    newContent.querySelector('.product_id').value = '';
    newContent.querySelector('.product_type').value = '';
    newContent.querySelector('.qty').value = 1;
    newContent.querySelector('.price').value = '';
    newContent.querySelector('.scheduled_date').value = '';
    newContent.querySelector('.scheduled_time').value = '';
    newContent.querySelector('.staff_nik').selectedIndex = 0;
    newContent.querySelector('.location').value = '';
    newContent.querySelector('.sum-total').textContent = 'Rp. 0';

    activateTab(newTab);
    initTabForm(newContent);
  });

  btnRem.addEventListener('click', ()=>{
    const active = document.querySelector('.tab.active');
    if(!active) return;
    const id = active.dataset.tab;
    const content = document.querySelector(`.tab-content[data-tab="${id}"]`);
    if(tabs.children.length > 1){
      active.remove();
      content.remove();
      activateTab(tabs.lastElementChild);
    }else{
      alert('Minimal harus ada satu tab transaksi!');
    }
  });

  tabs.addEventListener('click', (e)=>{
    if(e.target.classList.contains('tab')) activateTab(e.target);
  });

  function initTabForm(wrapper){
    // lokasi auto dari staff
    const staffSelect = wrapper.querySelector('.staff_nik');
    staffSelect.addEventListener('change', e=>{
      const opt = e.target.selectedOptions[0];
      wrapper.querySelector('.location').value = opt?.dataset.location || '';
    });

    attachProductSearch(wrapper);

    // pastikan tidak dobel listener
    const oldBtn = wrapper.querySelector('.btn-tambah-ke-ringkasan');
    const newBtn = oldBtn.cloneNode(true);
    oldBtn.replaceWith(newBtn);
    newBtn.addEventListener('click', ()=>{
  tambahKeRingkasan(wrapper);
  if (typeof window.refreshRekom === 'function') {
    window.refreshRekom();          // tab hasil clone pun auto refresh
  }
});
  }

  function attachProductSearch(wrapper){
    const input  = wrapper.querySelector('.product-search');
    const container = wrapper.querySelector('.product-container');
    const list   = wrapper.querySelector('.dropdown-list');

    // reset dropdown
    list.innerHTML=''; list.style.display='none';

    input.addEventListener('input', async ()=>{
      const q = input.value.trim(); if(q.length < 2){ list.style.display='none'; return; }
      try{
        const res = await fetch(`{{ route('products.search') }}?q=${encodeURIComponent(q)}`);
        const data = await res.json();
        list.innerHTML = '';
        data.forEach(p=>{
          const opt = document.createElement('div');
          opt.className = 'dropdown-option';
          opt.textContent = `${p.name} - Rp${Number(p.price).toLocaleString('id-ID')}`;
          opt.dataset.id = p.id;
          opt.dataset.type = p.type;   // 'service' | 'med'
          opt.dataset.price = p.price;
          list.appendChild(opt);
        });
        list.style.display = data.length ? 'block' : 'none';
      }catch(err){ console.error(err); }
    });

    list.addEventListener('click', e=>{
      if(!e.target.classList.contains('dropdown-option')) return;

      const { id, type, price } = e.target.dataset;
      input.value = e.target.textContent.split(' - ')[0];
      wrapper.querySelector('.product_id').value   = id;
      wrapper.querySelector('.product_type').value = type;
      wrapper.querySelector('.price').value        = price;

      // toggle jadwal sesuai tipe
      const dateEl = wrapper.querySelector('.scheduled_date');
      const timeEl = wrapper.querySelector('.scheduled_time');
      if (type === 'service') {
        dateEl.removeAttribute('disabled');
        timeEl.removeAttribute('disabled');
      } else { // med
        dateEl.value = '';
        timeEl.value = '';
        dateEl.setAttribute('disabled', 'disabled');
        timeEl.setAttribute('disabled', 'disabled');
      }
      list.style.display = 'none';
    });

    document.addEventListener('click', (e)=>{
      if(!container.contains(e.target)) list.style.display='none';
    });
  }

  function tambahKeRingkasan(wrapper){
    // ambil nilai awal
    const nameEl   = wrapper.querySelector('.product-search');
    const qtyEl    = wrapper.querySelector('.qty');
    const priceEl  = wrapper.querySelector('.price');
    const idEl     = wrapper.querySelector('.product_id');
    const typeEl   = wrapper.querySelector('.product_type');
    const dateEl   = wrapper.querySelector('.scheduled_date');
    const timeEl   = wrapper.querySelector('.scheduled_time');
    const staffSel = wrapper.querySelector('.staff_nik');

    const name        = (nameEl?.value || '').trim();
    const qty         = parseInt(qtyEl?.value || '0', 10) || 0;
    const price       = parseFloat(priceEl?.value || '0') || 0;
    const prodId      = parseInt(idEl?.value || '0', 10) || 0;
    const prodTypeVal = (typeEl?.value || '').trim();
    const dateVal     = (dateEl?.value || '').trim();
    const timeVal     = (timeEl?.value || '').trim();

    const staffNik    = staffSel?.value || '';
    const staffName   = staffSel?.selectedOptions?.[0]?.text.split(' (')[0] || '-';
    const loc         = staffSel?.selectedOptions?.[0]?.dataset.location || '';

    // Validasi FE
    if (!name || !price || !prodId || !prodTypeVal){
      alert('Lengkapi produk (pilih dari dropdown) dan harga.');
      return;
    }
    if (!staffNik){
      alert('Pilih staff terlebih dahulu.');
      return;
    }
    if (prodTypeVal === 'service'){
      if (!dateVal || !timeVal){
        alert('Service wajib punya tanggal & jam.');
        return;
      }
    } else { // med
      if (dateVal || timeVal){
        alert('Produk MED tidak boleh memiliki tanggal/jam.');
        return;
      }
    }

    const sub = qty * price;

    const tbody = wrapper.querySelector('.sum-body');
    if (tbody.querySelector('.muted')) tbody.innerHTML = '';

    const tr = document.createElement('tr');
    tr.dataset.productId   = String(prodId);
    tr.dataset.productType = prodTypeVal;

    tr.innerHTML = `
      <td>${name}</td>
      <td>${qty}</td>
      <td>Rp. ${price.toLocaleString('id-ID')}</td>
      <td>Rp. ${sub.toLocaleString('id-ID')}</td>
      <td data-nik="${staffNik}">${staffName}</td>
      <td>${loc}</td>
      <td>${dateVal}</td>
      <td>${timeVal}</td>
      <td><button type="button" class="btn-hapus-produk btn btn-sm btn-outline-danger">Hapus</button></td>
    `;
    tbody.appendChild(tr);

    tr.querySelector('.btn-hapus-produk').addEventListener('click', ()=>{
      tr.remove();
      hitungTotal(wrapper);
      if(!tbody.children.length){
        tbody.innerHTML = '<tr><td colspan="9" class="muted">Belum ada item.</td></tr>';
      }
    });

    if (typeof window.refreshRekom === 'function') {
    window.refreshRekom();          // panggil refresher rekom setiap berhasil tambah
  }

    hitungTotal(wrapper);

    // reset minimal
    nameEl.value  = '';
    idEl.value    = '';
    typeEl.value  = '';
    qtyEl.value   = 1;
    priceEl.value = '';
    // date/time tetap mengikuti toggle produk berikutnya
  }

  function hitungTotal(wrapper){
    let tot = 0;
    wrapper.querySelectorAll('.sum-body tr').forEach(tr=>{
      const subText = tr.cells[3]?.textContent || '';
      const sub = parseInt(subText.replace(/[^\d]/g,'')) || 0;
      tot += sub;
    });
    wrapper.querySelector('.sum-total').textContent = 'Rp. ' + tot.toLocaleString('id-ID');
  }

  // init tab pertama
  initTabForm(document.querySelector('.tab-content.active'));
});

// =============== PROSES TRANSAKSI (MULTI CLIENT) ===================
document.getElementById('btn-proses').addEventListener('click', async ()=>{
  const allTabs = document.querySelectorAll('.tab-content');
  const allClients = [];

  allTabs.forEach(tab=>{
    const clientData = {
      client_name: tab.querySelector('.client_name').value.trim(),
      age: tab.querySelector('.age').value || null,
      occupation: tab.querySelector('.occupation').value.trim(),
      sex: tab.querySelector('.sex').value || null,
      channel: 'Point Of Sale'
    };

    const items = [];
    tab.querySelectorAll('.sum-body tr').forEach(tr=>{
      if(tr.querySelector('.muted')) return;
      const tds = tr.querySelectorAll('td');
      items.push({
        product_id: parseInt(tr.dataset.productId) || 0,
        product_name: tds[0].textContent.trim(),
        product_type: tr.dataset.productType || 'service',
        qty: parseInt(tds[1].textContent.replace(/\D/g,'')) || 1,
        price: parseFloat(tds[2].textContent.replace(/[^\d]/g,'')) || 0,
        staff_nik: tds[4].dataset.nik || '',
        location: tds[5].textContent.trim(),
        scheduled_date: tds[6].textContent.trim() || null,
        scheduled_time: tds[7].textContent.trim() || null,
        status: 'NEW'
      });
    });

    if(clientData.client_name && items.length > 0){
      allClients.push({ ...clientData, items });
    }
  });

  if(allClients.length === 0){
    alert('Belum ada transaksi yang valid.');
    return;
  }

  // kirim ke backend satu per satu
  for(const c of allClients){
    try{
      const res = await fetch("{{ route('transactions.store') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(c)
      });
      const data = await res.json();

      if(res.status === 422){
        const msgs = Object.values(data.errors || {}).flat().join('\n');
        alert('Validasi gagal pada salah satu tab:\n' + msgs);
        return;
      }

      if(!data.success){
        alert('Gagal menyimpan salah satu transaksi.');
        return;
      }
    }catch(err){
      console.error(err);
      alert('Terjadi kesalahan server.');
      return;
    }
  }

  alert('Semua transaksi berhasil disimpan!');
  window.location.href = "{{ route('list.page') }}";
});
</script>

<style>
.buat-wrap {
  width: 100%;
  display: flex;
  justify-content: center; /* bantu atur posisi umum */
  padding: 30px 0;
}

/* Tambah offset dari kiri supaya agak ke tengah */
.layout-34 {
  display: flex;
  gap: 20px;
  align-items: flex-start;
  width: 100%;
  max-width: 1200px; /* batasi lebar agar tidak melebar seluruh layar */
  margin-left: 140px; /* inilah yang bikin agak ke tengah */
}

.col-transaksi {
  flex: 0 0 75%;
}

.col-rekomendasi {
  flex: 0 0 23%;
  background: #fafafa;
  padding: 15px;
  border: 1px solid #e5e5e5;
  border-radius: 6px;
}
/* Tab */
.tab-bar{display:flex; justify-content:space-between; margin-bottom:15px;}
.tabs{display:flex; gap:6px; flex-wrap:wrap;}
.tab-actions{display:flex; gap:6px;}
.tab{background:#f3f3f3; border:1px solid #ccc; padding:6px 12px; border-radius:4px; cursor:pointer;}
.tab.active{background:#007bff; color:#fff; border-color:#007bff;}
.tab-content{display:none;}
.tab-content.active{display:block;}

/* Produk */
.product-container{position:relative;}
.dropdown-list{position:absolute; top:100%; left:0; width:100%; max-height:180px; overflow-y:auto; background:#fff; border:1px solid #ccc; z-index:50; display:none; border-radius:4px;}
.dropdown-option{padding:6px 8px; cursor:pointer;}
.dropdown-option:hover{background:#e7f1ff;}

/* Summary */
.summary{margin-top:20px;}
.table .tar{text-align:right}
.table .strong{font-weight:600}
.muted{color:#888}
@media (min-width: 1200px) {
  .layout-34 {
    margin-left: 12%; /* geser kanan secara proporsional */
  }
}
</style>
@endsection
