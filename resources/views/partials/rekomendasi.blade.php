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

  // ——— DEBOUNCE UTILITY ———
  function debounce(fn, delay){
    let timer;
    return function(...args){
      clearTimeout(timer);
      timer = setTimeout(()=> fn.apply(this, args), delay);
    }
  }

  // ——— FUNGSI UTAMA AMBIL REKOMENDASI ———
  async function ambilRekomendasi() {
    const w = getActiveTabWrapper();
    if(!w) return;

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
      const res = await fetch("{{ route('transactions.recommend') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ age: age || null, sex: sex || null, occupation: occupation || null })
      });

      const data = await res.json();
      rekomList.innerHTML = '';

      if(data.recommendations && data.recommendations.length > 0){
        data.recommendations.forEach(r=>{
          const li = document.createElement('li');
          li.classList.add('cursor-pointer', 'hover:text-blue-600');
          li.textContent = r.name || '-';
          li.dataset.id    = r.id;
          li.dataset.type  = r.type;
          li.dataset.price = r.price;

          // klik masuk ke input produk
          li.addEventListener('click', ()=>{
            const inputWrapper = getActiveTabWrapper();
            inputWrapper.querySelector('.product-search').value  = r.name;
            inputWrapper.querySelector('.product_id').value      = r.id;
            inputWrapper.querySelector('.product_type').value    = r.type;
            inputWrapper.querySelector('.price').value           = r.price;

            const dateEl = inputWrapper.querySelector('.scheduled_date');
            const timeEl = inputWrapper.querySelector('.scheduled_time');

            if(r.type === 'service'){
              dateEl.removeAttribute('disabled');
              timeEl.removeAttribute('disabled');
            } else { // med
              dateEl.value = '';
              timeEl.value = '';
              dateEl.setAttribute('disabled','disabled');
              timeEl.setAttribute('disabled','disabled');
            }
          });

          rekomList.appendChild(li);
        });
      } else {
        rekomList.innerHTML = '<li class="text-muted">Tidak ada rekomendasi ditemukan.</li>';
      }
    } catch(err){
      console.error(err);
      rekomList.innerHTML = '<li class="text-danger">Gagal mengambil rekomendasi.</li>';
    } finally {
      rekomLoading.classList.add('hidden');
    }
  }

  // wrap ambilRekomendasi dengan debounce
  const ambilDebounce = debounce(ambilRekomendasi, 400);

  // ——— EVENT LISTENER ———
  btnRefresh.addEventListener('click', ambilRekomendasi);

  document.addEventListener('input', e=>{
    if(!e.target.closest('.tab-content.active')) return;
    if(['age','sex','occupation'].some(cls=>e.target.classList.contains(cls))){
      ambilDebounce();
    }
  });

  document.addEventListener('change', e=>{
    if(!e.target.closest('.tab-content.active')) return;
    if(['age','sex','occupation'].some(cls=>e.target.classList.contains(cls))){
      ambilDebounce();
    }
  });

  // panggil pertama kali kalau sudah ada nilai
  ambilRekomendasi();
});
</script>
@endpush
