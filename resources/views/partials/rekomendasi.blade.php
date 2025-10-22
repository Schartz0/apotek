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

  const rekomList   = document.getElementById('rekom-list');
  const rekomLoading= document.getElementById('rekom-loading');
  const btnRefresh  = document.getElementById('btn-refresh-rekom');

  // ——— UTIL: ambil wrapper tab aktif ———
  function getActiveTabWrapper(){
    return document.querySelector('.tab-content.active') || document.querySelector('.tab-content');
  }

  // ——— FUNGSI UTAMA: ambil rekom berdasarkan tab aktif ———
  async function ambilRekomendasi() {
    const w = getActiveTabWrapper();
    if (!w) return;

    const age        = (w.querySelector('.age')?.value || '').trim();
    const sex        = (w.querySelector('.sex')?.value || '').trim();
    const occupation = (w.querySelector('.occupation')?.value || '').trim();

    // Jika belum ada data penting, jangan fetch
    if (!age && !sex && !occupation) {
      rekomList.innerHTML = '<li class="text-muted">Isi data klien untuk melihat rekomendasi.</li>';
      return;
    }

    const client = {
      age: age || null,
      sex: sex || null,
      occupation: occupation || null,
    };

    rekomLoading.classList.remove('hidden');
    rekomList.innerHTML = '';

    try {
      const res = await fetch("{{ route('transactions.recommend') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(client)
      });

      const data = await res.json();
      rekomList.innerHTML = '';

      if (data.recommendations && data.recommendations.length > 0) {
        data.recommendations.forEach(r => {
          const li = document.createElement('li');
          // dukung kedua bentuk: string atau object {product_name}
          li.textContent = typeof r === 'string' ? r : (r.product_name ?? r.name ?? '-');
          rekomList.appendChild(li);
        });
      } else {
        rekomList.innerHTML = '<li class="text-muted">Tidak ada rekomendasi ditemukan.</li>';
      }

    } catch (err) {
      console.error(err);
      rekomList.innerHTML = '<li class="text-danger">Gagal mengambil rekomendasi.</li>';
    } finally {
      rekomLoading.classList.add('hidden');
    }
  }

  // ——— EKSPOR KE GLOBAL: bisa dipanggil dari file lain ———
  window.refreshRekom = ambilRekomendasi;

  // ——— Refresh manual via tombol ———
  btnRefresh.addEventListener('click', ambilRekomendasi);

  // ——— Auto-refresh saat user mengubah demografi di TAB AKTIF ———
  document.addEventListener('input', (e)=>{
    if(!e.target.closest('.tab-content.active')) return;
    if (e.target.classList.contains('age') ||
        e.target.classList.contains('sex') ||
        e.target.classList.contains('occupation')) {
      ambilRekomendasi();
    }
  });
  document.addEventListener('change', (e)=>{
    if(!e.target.closest('.tab-content.active')) return;
    if (e.target.classList.contains('age') ||
        e.target.classList.contains('sex') ||
        e.target.classList.contains('occupation')) {
      ambilRekomendasi();
    }
  });

  // ——— Auto-refresh setelah klik “Tambah ke Ringkasan” dari tab mana pun (event delegation) ———
  document.addEventListener('click', (e)=>{
    if (e.target.classList.contains('btn-tambah-ke-ringkasan')) {
      // biarkan proses tambah jalan dulu, lalu refresh
      setTimeout(ambilRekomendasi, 0);
    }
  });

  // ——— Auto-refresh saat user pindah tab ———
  document.getElementById('tab-container')?.addEventListener('click', (e)=>{
    if (e.target.classList.contains('tab')) {
      // beri jeda tipis agar class .active sudah pindah
      setTimeout(ambilRekomendasi, 0);
    }
  });

  // panggil awal (kalau sudah ada nilai)
  ambilRekomendasi();
});
</script>
@endpush
