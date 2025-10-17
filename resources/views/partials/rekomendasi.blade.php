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

  const rekomList = document.getElementById('rekom-list');
  const rekomLoading = document.getElementById('rekom-loading');
  const btnRefresh = document.getElementById('btn-refresh-rekom');

  async function ambilRekomendasi() {
    const firstTab = document.querySelector('.tab-content[data-tab="1"]');
    if (!firstTab) return;

    const client = {
      age: firstTab.querySelector('.age').value || null,
      sex: firstTab.querySelector('.sex').value || null,
      occupation: firstTab.querySelector('.occupation').value.trim() || null,
    };

    // Jika belum ada data penting, jangan fetch
    if (!client.age && !client.sex && !client.occupation) {
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
        body: JSON.stringify(client)
      });

      const data = await res.json();
      rekomList.innerHTML = '';

      if (data.recommendations && data.recommendations.length > 0) {
        data.recommendations.forEach(r => {
          const li = document.createElement('li');
          li.textContent = r;
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

  // Refresh manual via tombol
  btnRefresh.addEventListener('click', ambilRekomendasi);

  // Auto-refresh setelah user isi form client
  ['age', 'sex', 'occupation'].forEach(sel => {
    document.querySelector(`.tab-content[data-tab="1"] .${sel}`)
      .addEventListener('change', ambilRekomendasi);
    document.querySelector(`.tab-content[data-tab="1"] .${sel}`)
      .addEventListener('input', ambilRekomendasi);
  });

});
</script>
@endpush
