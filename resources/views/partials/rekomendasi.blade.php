{{-- resources/views/partials/rekomendasi.blade.php --}}
<div class="col-rekomendasi">
  <h5>💡 Rekomendasi Produk</h5>

  <div id="rekom-loading" class="text-muted hidden">Memproses rekomendasi...</div>
  <ul id="rekom-list" class="list-disc pl-4 text-sm text-gray-700 space-y-1">
    <li class="text-muted">Isi data klien untuk melihat rekomendasi.</li>
  </ul>

  <button type="button" class="btn btn-sm btn-outline-primary w-full mt-3" id="btn-refresh-rekom">
    🔄 Refresh Rekomendasi
  </button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', ()=>{

  const rekomList    = document.getElementById('rekom-list');
  const rekomLoading = document.getElementById('rekom-loading');
  const btnRefresh   = document.getElementById('btn-refresh-rekom');

  function getActiveTabWrapper(){
    return document.querySelector('.tab-content.active') || document.querySelector('.tab-content');
  }

  function debounce(fn, delay){
    let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a), delay); };
  }

  async function ambilRekomendasi() {
    const w = getActiveTabWrapper(); if(!w) return;

    const age        = (w.querySelector('.age')?.value || '').trim();
    const sex        = (w.querySelector('.sex')?.value || '').trim();
    const occupation = (w.querySelector('.occupation')?.value || '').trim();

    if(!age && !sex && !occupation){
      rekomList.innerHTML = '<li class="text-muted">Isi data klien untuk melihat rekomendasi.</li>';
      return;
    }

    rekomLoading.classList.remove('hidden');
    rekomList.innerHTML = '';

    try {
      // BE mengembalikan [{product_id, product_type}]
      const res = await fetch("{{ route('transactions.recommend') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ age: age || null, sex: sex || null, occupation: occupation || null })
      });
      const data = await res.json();

      rekomList.innerHTML = '';

      const recos = Array.isArray(data?.recommendations) ? data.recommendations : [];
      if (recos.length === 0) {
        rekomList.innerHTML = '<li class="text-muted">Tidak ada rekomendasi ditemukan.</li>';
        return;
      }

      // Lookup detail produk berdasarkan product_id (parallel)
      const details = await Promise.all(recos.map(async (r)=>{
        const pid = r.product_id ?? r.id ?? null;
        const ptype = r.product_type ?? r.type ?? null;
        if (!pid) return null;

        try {
          const resp = await fetch(`{{ route('products.search') }}?q=${encodeURIComponent(pid)}`);
          const arr = await resp.json(); // array of candidates
          // pilih yang id cocok; prefer yang type cocok juga kalau ada
          let d = Array.isArray(arr) ? arr.find(x => String(x.id) === String(pid) && (!ptype || x.type === ptype)) : null;
          if (!d && Array.isArray(arr) && arr.length) d = arr[0];
          return d ? { ...d, product_id: pid } : { id: pid, name: `Produk #${pid}`, type: ptype ?? '-', price: null };
        } catch (_) {
          return { id: pid, name: `Produk #${pid}`, type: ptype ?? '-', price: null };
        }
      }));

      // Render list + klik → prefill
      for (const d of details) {
        if (!d) continue;
        const li = document.createElement('li');
        li.classList.add('cursor-pointer','hover:text-blue-600');

        const label = d.price != null
          ? `${d.name} (${d.type}) — Rp${Number(d.price).toLocaleString('id-ID')}`
          : `${d.name} (${d.type})`;

        li.textContent = label;
        li.dataset.id    = d.id;
        li.dataset.type  = d.type;
        if (d.price != null) li.dataset.price = d.price;

        li.addEventListener('click', ()=>{
          const iw = getActiveTabWrapper();
          iw.querySelector('.product-search').value = d.name;
          iw.querySelector('.product_id').value     = d.id;
          iw.querySelector('.product_type').value   = d.type;
          if (d.price != null) iw.querySelector('.price').value = d.price;

          const dateEl = iw.querySelector('.scheduled_date');
          const timeEl = iw.querySelector('.scheduled_time');
          if (d.type === 'service') {
            dateEl.removeAttribute('disabled'); timeEl.removeAttribute('disabled');
          } else {
            dateEl.value=''; timeEl.value='';
            dateEl.setAttribute('disabled','disabled'); timeEl.setAttribute('disabled','disabled');
          }
        });

        rekomList.appendChild(li);
      }

    } catch (err) {
      console.error(err);
      rekomList.innerHTML = '<li class="text-danger">Gagal mengambil rekomendasi.</li>';
    } finally {
      rekomLoading.classList.add('hidden');
    }
  }

  const ambilDebounce = debounce(ambilRekomendasi, 400);

  btnRefresh.addEventListener('click', ambilRekomendasi);

  document.addEventListener('input', e=>{
    if(!e.target.closest('.tab-content.active')) return;
    if (e.target.classList.contains('age') ||
        e.target.classList.contains('sex') ||
        e.target.classList.contains('occupation')) {
      ambilDebounce();
    }
  });
  document.addEventListener('change', e=>{
    if(!e.target.closest('.tab-content.active')) return;
    if (e.target.classList.contains('age') ||
        e.target.classList.contains('sex') ||
        e.target.classList.contains('occupation')) {
      ambilDebounce();
    }
  });

  // supaya bisa dipanggil dari buat.blade setelah "Tambah ke Ringkasan"
  window.refreshRekom = ambilRekomendasi;

  // panggil awal jika sudah ada nilai
  ambilRekomendasi();
});
</script>
@endpush

