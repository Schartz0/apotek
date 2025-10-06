const q = new URLSearchParams(location.search);
const fmtRp = n => 'Rp. ' + Number(n||0).toLocaleString('id-ID');

function loadList() {
  try { return JSON.parse(localStorage.getItem('tx_list') || '[]'); }
  catch { return []; }
}

function renderDetail() {
  const id = Number(q.get('id'));
  const all = loadList();
  const tx = all.find(t => Number(t.id) === id);

  const root = document.getElementById('detail-root');
  const nf = document.getElementById('notfound');
  if (!tx) {
    nf.style.display = 'block';
    return;
  }
  root.style.display = 'block';

  // header info
  document.getElementById('det-id').textContent = '#' + String(tx.id).padStart(4,'0');
  const d = new Date(tx.created_at);
  document.getElementById('det-date').textContent = d.toLocaleString('id-ID', {year:'numeric',month:'2-digit',day:'2-digit',hour:'2-digit',minute:'2-digit'});
  document.getElementById('det-client').textContent = tx.client_name || '-';
  document.getElementById('det-by').textContent = tx.created_by || 'Admin 1';
  document.getElementById('det-total').textContent = fmtRp(tx.total || 0);

  // split service & obat dari items
  const service = (tx.items || []).filter(i => i.type === 'service');
  const obat    = (tx.items || []).filter(i => i.type === 'obat');

  // SERVICE table
  const tbS = document.getElementById('tbody-service');
  if (service.length === 0) {
    tbS.innerHTML = `<tr><td colspan="11" class="muted">Tidak ada service.</td></tr>`;
  } else {
    tbS.innerHTML = service.map((it, idx) => {
      const time = it.scheduled.includes(' ')
        ? it.scheduled.split(' ')[1] : '-';
      const date = it.scheduled.includes(' ')
        ? it.scheduled.split(' ')[0] : '-';
      return `
        <tr>
          <td>TXS${String(tx.id).slice(-4)}-${idx+1}</td>
          <td>${date}</td>
          <td>${tx.created_by || 'Admin 1'}</td>
          <td>${tx.client_name || '-'}</td>
          <td>${it.name || '-'}</td>
          <td>${date}</td>
          <td>${time}</td>
          <td>${it.qtyDur || '-'}</td>
          <td>${tx.staff_name || '-'}</td>
          <td class="tar">${fmtRp(it.subtotal)}</td>
          <td>${it.status || 'COMPLETED'}</td>
        </tr>
      `;
    }).join('');
  }

  // OBAT table
  const tbO = document.getElementById('tbody-obat');
  if (obat.length === 0) {
    tbO.innerHTML = `<tr><td colspan="6" class="muted">Tidak ada obat.</td></tr>`;
  } else {
    tbO.innerHTML = obat.map(it => `
      <tr>
        <td>${d.toLocaleDateString('id-ID')}</td>
        <td>${tx.created_by || 'Admin 1'}</td>
        <td>${tx.client_name || '-'}</td>
        <td>${it.name || '-'}</td>
        <td>${(it.qtyDur || '0 pcs').replace(' pcs','')}</td>
        <td class="tar">${fmtRp(it.subtotal)}</td>
      </tr>
    `).join('');
  }
}

document.addEventListener('DOMContentLoaded', renderDetail);
//