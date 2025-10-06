/** =========================
 *  Helpers & Dummy Data
 * ========================= */
const rupiah = (n) => 'Rp. ' + Number(n || 0).toLocaleString('id-ID');
const qs = (root, s) => root.querySelector(s);
const show = (el, yes) => el.classList.toggle('hidden', !yes);
const clearInput  = (el) => { if (el) el.value = ''; };
const clearSelect = (el) => { if (el) el.selectedIndex = 0; };

const REKOMENDASI = {
  service: [
    'SKIN BOOSTER KOREA SHINE',
    'Dermabration - Vacuum Pore',
    'IPL Face Whitening / Acne'
  ],
  obat: [
    'Phil Whitening',
    'Brightening cream'
  ]
};

/** =========================
 *  Panel Rekomendasi
 *  - Jenis → header saja
 *  - Nama  → header + list
 * ========================= */
function updateRekomendasi(jenis, nama /* boleh null */) {
  const head = document.querySelector('.js-reco-head');
  const list = document.querySelector('.js-reco-list');
  if (!head || !list) return;

  if (!jenis) {
    head.innerHTML = 'Pilih <em>Jenis</em> terlebih dahulu.';
    list.innerHTML = '';
    return;
  }

  const label = jenis === 'service' ? 'Service' : 'Obat';
  head.innerHTML = `<strong>${label} :</strong>`;

  // belum ada nama → header saja
  if (!nama) {
    list.innerHTML = '';
    return;
  }

  // sudah ada nama → tampilkan list
  const items = REKOMENDASI[jenis] || [];
  list.innerHTML = items.map(item => {
    const active = item.toLowerCase() === nama.toLowerCase() ? 'active' : '';
    return `<div class="reco-item ${active}">${item}</div>`;
  }).join('');
}

/** =========================
 *  State
 * ========================= */
const state = { items: [] }; // {id,type,name,scheduled,qtyDur,subtotal,valid}

/** =========================
 *  Readers
 * ========================= */
function readService(block) {
  const name = qs(block, '.js-nama-service')?.value || '';
  const date = qs(block, '.js-date')?.value || '';
  const time = qs(block, '.js-time')?.value || '';
  const dur  = qs(block, '.js-duration')?.value || '';
  const price = Number(qs(block, '.js-price')?.value || 0);

  const valid = !!(name && date && time && dur && price > 0);
  return {
    type: 'service',
    name,
    scheduled: (date && time) ? `${date} ${time}` : '-',
    qtyDur: `${dur} min`,
    subtotal: price,
    valid
  };
}

function readObat(block) {
  const name  = qs(block, '.js-nama-obat')?.value || '';
  const qty   = Number(qs(block, '.js-qty')?.value || 0);
  const price = Number(qs(block, '.js-price-obat')?.value || 0);

  const valid = !!(name && qty > 0 && price > 0);
  return {
    type: 'obat',
    name,
    scheduled: '-',
    qtyDur: `${qty} pcs`,
    subtotal: price * qty,
    valid
  };
}

/** =========================
 *  Summary Renderer
 * ========================= */
function renderSummary() {
  const body = document.getElementById('sum-body');
  const totalEl = document.getElementById('sum-total');
  const prosesBtn = document.getElementById('btn-proses');

  const rows = state.items.filter(i => i && i.valid);
  if (!rows.length) {
    body.innerHTML = `<tr><td colspan="5" class="muted">Belum ada item.</td></tr>`;
    totalEl.textContent = 'Rp. 0';
    if (prosesBtn) {
      prosesBtn.disabled = true;
      prosesBtn.classList.add('btn-disabled');
      prosesBtn.classList.remove('btn-primary');
    }
    return;
  }

  body.innerHTML = rows.map(r => `
    <tr>
      <td>${r.type === 'service' ? 'Service' : 'Obat'}</td>
      <td>${r.name}</td>
      <td>${r.scheduled}</td>
      <td>${r.qtyDur}</td>
      <td class="tar">${rupiah(r.subtotal)}</td>
    </tr>
  `).join('');

  const total = rows.reduce((a,b)=>a + (Number(b.subtotal)||0), 0);
  totalEl.textContent = rupiah(total);
  if (prosesBtn) {
    prosesBtn.disabled = false;
    prosesBtn.classList.remove('btn-disabled');
    prosesBtn.classList.add('btn-primary');
  }
}

/** =========================
 *  Plus visibility (muncul hanya blok terakhir & valid)
 * ========================= */
function refreshPlusButtons() {
  const blocks = document.querySelectorAll('.order-block');
  blocks.forEach((b, i) => {
    const plus = qs(b, '.js-plus');
    const valid = !!state.items[i]?.valid;
    const last = i === blocks.length - 1;
    if (plus) plus.style.display = (last && valid) ? 'inline-flex' : 'none';
  });
}

/** =========================
 *  Reset Block
 * ========================= */
function resetBlock(block, keepJenis) {
  const jenisSel = qs(block, '.js-jenis');
  const svc = qs(block, '.js-fields-service');
  const obt = qs(block, '.js-fields-obat');

  if (!keepJenis) {
    clearSelect(jenisSel);
    show(svc, false);
    show(obt, false);
  } else {
    const jenis = jenisSel.value;
    show(svc, jenis === 'service');
    show(obt, jenis === 'obat');
  }

  // kosongkan semua input/select di dalam block (kecuali select .js-jenis kalau keepJenis)
  block.querySelectorAll('input').forEach(el => el.value = '');
  block.querySelectorAll('select').forEach(el => {
    if (!keepJenis || !el.classList.contains('js-jenis')) el.selectedIndex = 0;
  });
}

/** =========================
 *  Recalc Block (TIDAK menyentuh rekomendasi)
 * ========================= */
function recalc(block) {
  const idx = Number(block.dataset.index);
  const jenis = qs(block, '.js-jenis').value;

  let data = { valid:false, subtotal:0, name:'' };
  if (jenis === 'service') data = readService(block);
  if (jenis === 'obat')    data = readObat(block);

  state.items[idx] = { id: idx, ...data };

  renderSummary();
  refreshPlusButtons();
}

/** =========================
 *  Add & Attach
 * ========================= */
function addBlock() {
  const wrap = document.getElementById('orders');
  const tpl = document.getElementById('tpl-order');
  const node = tpl.content.firstElementChild.cloneNode(true);
  node.dataset.index = wrap.children.length;
  wrap.appendChild(node);

  state.items.push({ id: wrap.children.length - 1, valid:false, subtotal:0 });
  attachEvents(node);
  refreshPlusButtons();
}

function attachEvents(block) {
  const jenisSel = qs(block, '.js-jenis');
  const svc = qs(block, '.js-fields-service');
  const obt = qs(block, '.js-fields-obat');
  const minus = qs(block, '.js-minus');
  const plus  = qs(block, '.js-plus');

  // === Jenis diubah → tampil field, KOSONGKAN nama, dan update rekomendasi (HEADER SAJA)
  jenisSel.addEventListener('change', () => {
    const val = jenisSel.value;

    // tampilkan field sesuai jenis
    show(svc, val === 'service');
    show(obt, val === 'obat');

    // kosongkan pilihan nama (agar tidak dianggap sudah memilih)
    clearSelect(qs(block, '.js-nama-service'));
    clearSelect(qs(block, '.js-nama-obat'));

    // rekomendasi: header saja
    updateRekomendasi(val || null, null);

    // hitung ulang state (tanpa menyentuh rekomendasi di dalam recalc)
    recalc(block);
  });

  // === Nama dipilih → barulah tampil list recomendasi
  qs(block, '.js-nama-service')?.addEventListener('change', () => {
    const jenis = 'service';
    const nama  = qs(block, '.js-nama-service').value || null;
    updateRekomendasi(jenis, nama);
    recalc(block);
  });
  qs(block, '.js-nama-obat')?.addEventListener('change', () => {
    const jenis = 'obat';
    const nama  = qs(block, '.js-nama-obat').value || null;
    updateRekomendasi(jenis, nama);
    recalc(block);
  });

  // === Input lain → hanya recalc (tidak menyentuh rekomendasi)
  block.addEventListener('input', (e) => {
    // abaikan perubahan pada select nama karena sudah ditangani khusus di atas
    if (e.target.matches('.js-nama-service, .js-nama-obat')) return;
    recalc(block);
  });

  // === MINUS
  minus.addEventListener('click', (e) => {
    e.preventDefault();
    const idx = Number(block.dataset.index);
    const isFirst = idx === 0;
    resetBlock(block, isFirst);
    state.items[idx] = { id: idx, valid:false, subtotal:0, name:'' };
    renderSummary();
    refreshPlusButtons();
    updateRekomendasi(null, null); // kembali ke state awal
  });

  // === PLUS
  plus.addEventListener('click', (e) => {
    e.preventDefault();
    addBlock();
  });
}

/** =========================
 *  Init Buat Page
 * ========================= */
function collectValidItems() {
  // Ambil hanya item valid dari state (dibuat di file ini)
  return (state.items || []).filter(it => it && it.valid).map(it => ({
    type: it.type,
    name: it.name,
    scheduled: it.scheduled,
    qtyDur: it.qtyDur,
    subtotal: Number(it.subtotal) || 0
  }));
}

function totalOf(items) {
  return items.reduce((a,b)=>a + (Number(b.subtotal)||0), 0);
}

function initBuatPage() {
  const page = document.querySelector('.buat-wrap');
  if (!page) return;

  addBlock();

  const proses = document.getElementById('btn-proses');
  proses?.addEventListener('click', (e) => {
    if (proses.disabled) return e.preventDefault();

    const items = collectValidItems();
    const total = totalOf(items);

    // ambil client & created by (dummy FE)
    const client = document.querySelector('.js-client')?.value?.trim() || '-';
    const createdBy = 'Admin 1'; // sesuai header

    // simpan ke localStorage
    const key = 'tx_list';
    const current = JSON.parse(localStorage.getItem(key) || '[]');
    const newTx = {
      id: Date.now(),               // simple unique id
      created_at: new Date().toISOString(),
      created_by: createdBy,
      client_name: client,
      items,
      total
    };
    current.push(newTx);
    localStorage.setItem(key, JSON.stringify(current));

    // redirect ke /list
    window.location.href = '/list';
  });
}

document.addEventListener('DOMContentLoaded', initBuatPage);
