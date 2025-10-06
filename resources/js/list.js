// Utils
const fmtRp = (n) => 'Rp. ' + Number(n || 0).toLocaleString('id-ID');
const byId = (id) => document.getElementById(id);

// Ambil dari localStorage
function loadTx() {
  try {
    return JSON.parse(localStorage.getItem('tx_list') || '[]');
  } catch { return []; }
}
function saveTx(list) {
  localStorage.setItem('tx_list', JSON.stringify(list));
}

// Render table
function renderList() {
  const tbody = byId('list-body');
  if (!tbody) return; // hanya di /list

  const data = loadTx();
  if (!data.length) {
    tbody.innerHTML = `<tr><td colspan="6" class="muted">Belum ada transaksi.</td></tr>`;
    return;
  }

  tbody.innerHTML = data.map((tx) => {
  const date = new Date(tx.created_at);
  const dateStr = date.toLocaleString('id-ID', {
    year:'numeric', month:'2-digit', day:'2-digit', hour:'2-digit', minute:'2-digit'
  });
  return `
    <tr data-id="${tx.id}">
      <td>#${String(tx.id).padStart(4,'0')}</td>
      <td>${dateStr}</td>
      <td>${tx.created_by || 'Admin 1'}</td>
      <td>${tx.client_name || '-'}</td>
      <td class="tar">${fmtRp(tx.total || 0)}</td>
      <td class="actions-cell">
        <a href="/detail?id=${tx.id}" class="link-detail">Detail</a>
        <button class="btn btn-danger btn-del" data-id="${tx.id}" style="height:34px">Hapus</button>
      </td>
    </tr>
  `;
}).join('');

  // Hapus
  tbody.querySelectorAll('.btn-del').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = Number(btn.dataset.id);
      const list = loadTx().filter(t => Number(t.id) !== id);
      saveTx(list);
      renderList();
    });
  });
}

document.addEventListener('DOMContentLoaded', renderList);
