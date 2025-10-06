import './bootstrap';
// Toggle dropdowns di navbar
document.addEventListener('click', (e) => {
  // tutup semua
  document.querySelectorAll('.nav-dropdown.open').forEach(dd => dd.classList.remove('open'));
  // buka yang diklik
  const btn = e.target.closest('[data-dd]');
  if (btn) {
    e.preventDefault();
    e.stopPropagation();
    btn.parentElement.classList.toggle('open');
  }
});
// tombol login → redirect dummy
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.btn-login');
  if (btn) {
    e.preventDefault();
    window.location.href = '/draft';
  }
});

// Dropdown navbar
document.addEventListener('click', (e) => {
  document.querySelectorAll('.nav-dropdown.open').forEach(dd => dd.classList.remove('open'));
  const btn = e.target.closest('[data-dd]');
  if (btn) {
    e.preventDefault();
    e.stopPropagation();
    btn.parentElement.classList.toggle('open');
  }
});

// Login dummy → /draft
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.btn-login');
  if (btn) {
    e.preventDefault();
    window.location.href = '/draft';
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