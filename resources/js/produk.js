// ======= Helpers & Storage =======
(() => {
  const pqs = (r, s) => r.querySelector(s);
  const pby = (s) => document.querySelector(s);
  const fmtId = (n) => String(n).padStart(1, '');
  const STORAGE = {
    service: 'prod_services',
    obat: 'prod_obats',
  };
  const load = (key) => { try { return JSON.parse(localStorage.getItem(key) || '[]'); } catch { return []; } };
  const save = (key, data) => localStorage.setItem(key, JSON.stringify(data));
  const toast = (msg) => alert(msg);

  // ======= Main =======
  function initProdukPage() {
    const root = pby('.produk-wrap');
    if (!root) return; // ⬅️ penting: hentikan script di halaman lain (misal /buat)

    const type = root.dataset.type; // 'service' | 'obat'
    const KEY = STORAGE[type];

    const tbody = pqs(root, '.js-tbody');
    const total = pqs(root, '.js-total');
    const name = pqs(root, '.js-name');
    const stock = pqs(root, '.js-stock');
    const addBtn = pqs(root, '.js-add');

    const modal = pby('#modal-edit');
    const mTitle = pqs(modal, '.js-edit-title');
    const mName = pqs(modal, '.js-edit-name');
    const mStock = pqs(modal, '.js-edit-stock');
    const mSave = pqs(modal, '.js-save');
    const mCloseBtns = modal.querySelectorAll('.js-close');

    const seed = (t) => {
      if (load(KEY).length) return;
      const base = t === 'service'
        ? [{ id: 1, name: 'Skin Booster', stock: 'Ada' }, { id: 2, name: 'Babyskin Crystal', stock: 'Kosong' }]
        : [{ id: 1, name: 'Phil Whitening', stock: 'Ada' }, { id: 2, name: 'Brightening Cream', stock: 'Kosong' }];
      save(KEY, base);
    };
    seed(type);

    function render() {
      const list = load(KEY);
      total.textContent = list.length;
      if (!list.length) {
        tbody.innerHTML = `<tr><td colspan="4" class="muted">Belum ada data.</td></tr>`;
        return;
      }
      tbody.innerHTML = list.map(it => `
        <tr data-id="${it.id}">
          <td>${fmtId(it.id)}</td>
          <td>${it.name}</td>
          <td>${it.stock}</td>
          <td>
            <a href="#" class="link-detail js-edit">Edit</a>
            <span> | </span>
            <a href="#" class="link-danger js-del">Hapus</a>
          </td>
        </tr>
      `).join('');

      tbody.querySelectorAll('.js-edit').forEach(a => {
        a.addEventListener('click', (e) => {
          e.preventDefault();
          const id = Number(e.target.closest('tr').dataset.id);
          const item = load(KEY).find(x => x.id === id);
          if (!item) return;
          mTitle.textContent = item.name;
          mName.value = item.name;
          mStock.value = item.stock;
          mSave.disabled = true;
          modal.classList.add('show');
          modal.setAttribute('aria-hidden', 'false');
          mName.focus();

          const checkValid = () => {
            const nm = mName.value.trim();
            const st = mStock.value;
            const changed = (nm !== item.name || st !== item.stock);
            const dup = load(KEY).some(x => x.id !== id && x.name.toLowerCase() === nm.toLowerCase());
            mSave.disabled = !(nm && st && changed) || dup;
          };
          mName.oninput = mStock.onchange = checkValid;

          const onSave = () => {
            const nm = mName.value.trim();
            const st = mStock.value;
            const list = load(KEY);
            if (list.some(x => x.id !== id && x.name.toLowerCase() === nm.toLowerCase())) {
              toast('Nama sudah ada.');
              return;
            }
            const idx = list.findIndex(x => x.id === id);
            if (idx > -1) { list[idx] = { ...list[idx], name: nm, stock: st }; save(KEY, list); }
            closeModal();
            render();
          };
          mSave.onclick = onSave;

          mCloseBtns.forEach(btn => btn.onclick = closeModal);
          modal.onkeydown = (ev) => { if (ev.key === 'Escape') closeModal(); if (ev.key === 'Enter' && !mSave.disabled) onSave(); };
          function closeModal() {
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
          }
        });
      });

      tbody.querySelectorAll('.js-del').forEach(a => {
        a.addEventListener('click', (e) => {
          e.preventDefault();
          const id = Number(e.target.closest('tr').dataset.id);
          if (!confirm('Hapus produk ini?')) return;
          const next = load(KEY).filter(x => x.id !== id);
          save(KEY, next); render();
        });
      });
    }

    render();

    function checkAddValid() {
      const nm = name.value.trim();
      const st = stock.value;
      const dup = load(KEY).some(x => x.name.toLowerCase() === nm.toLowerCase());
      addBtn.disabled = !(nm && st) || dup;
    }
    name.addEventListener('input', checkAddValid);
    stock.addEventListener('change', checkAddValid);

    addBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (addBtn.disabled) return;
      const nm = name.value.trim();
      const st = stock.value;
      const list = load(KEY);
      if (list.some(x => x.name.toLowerCase() === nm.toLowerCase())) {
        toast('Nama sudah ada.');
        return;
      }
      const id = (list.at(-1)?.id || 0) + 1;
      list.push({ id, name: nm, stock: st });
      save(KEY, list);
      name.value = ''; stock.value = ''; addBtn.disabled = true;
      render();
    });
  }

  document.addEventListener('DOMContentLoaded', initProdukPage);
})();
