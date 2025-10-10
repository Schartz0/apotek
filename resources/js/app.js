import './bootstrap';

// Toggle dropdowns di navbar
document.addEventListener('click', (e) => {
  // tutup semua dropdown yang terbuka
  document.querySelectorAll('.nav-dropdown.open').forEach(dd => dd.classList.remove('open'));
  
  // buka dropdown yang diklik
  const btn = e.target.closest('[data-dd]');
  if (btn) {
    e.preventDefault();
    e.stopPropagation();
    btn.parentElement.classList.toggle('open');
  }
});
// Flag table-wrap yang bisa discroll untuk memunculkan hint bayangan
function markScrollableWraps(){
  document.querySelectorAll('.table-wrap').forEach(w=>{
    if (w.scrollWidth > w.clientWidth) w.classList.add('is-scrollable');
    else w.classList.remove('is-scrollable');
  });
}
window.addEventListener('load', markScrollableWraps);
window.addEventListener('resize', markScrollableWraps);
document.addEventListener('DOMContentLoaded', markScrollableWraps);


// Halaman khusus
import './transaksi.js';  // buat halaman /buat
import './list.js';       // buat halaman /list
import './detail.js';     // buat halaman /detail
import './produk.js';     // buat halaman /produk/service & /produk/obat