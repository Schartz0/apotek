document.addEventListener('DOMContentLoaded', () => {
    let tabCount = 1;
    const tabs     = document.getElementById('tab-container');
    const contents = document.getElementById('tab-contents');
    const btnAdd   = document.getElementById('btn-add-tab');
    const btnRem   = document.getElementById('btn-remove-tab');

    // ---------- Tab ----------
    function activateTab(tab){
        document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
        tab.classList.add('active');
        document.querySelector(`.tab-content[data-tab="${tab.dataset.tab}"]`).classList.add('active');
    }

    btnAdd.addEventListener('click', ()=>{
        tabCount++;
        const newTab = document.createElement('button');
        newTab.className = 'tab'; newTab.dataset.tab = tabCount;
        newTab.textContent = 'Transaksi '+tabCount;

        const newContent = document.createElement('div');
        newContent.className = 'tab-content'; newContent.dataset.tab = tabCount;
        newContent.innerHTML = document.querySelector('.tab-content[data-tab="1"]').innerHTML;

        tabs.appendChild(newTab); contents.appendChild(newContent);
        activateTab(newTab); initTabForm(newContent);
    });

    btnRem.addEventListener('click', ()=>{
        const active = document.querySelector('.tab.active');
        if(!active) return;
        const id = active.dataset.tab;
        const content = document.querySelector(`.tab-content[data-tab="${id}"]`);
        if(tabs.children.length>1){ active.remove(); content.remove(); activateTab(tabs.lastElementChild); }
        else alert('Minimal harus ada satu tab transaksi!');
    });

    tabs.addEventListener('click', e=>{ if(e.target.classList.contains('tab')) activateTab(e.target); });

    // ---------- Produk ----------
    function initTabForm(wrapper){
        wrapper.querySelector('.ref_no').value = 'REF-'+Math.random().toString(36).substring(2,8).toUpperCase();

        // Pasang search & tombol pada setiap baris produk yang sudah ada
        wrapper.querySelectorAll('.produk-item').forEach(item=> attachProductSearch(item));

        // Tombol "Tambah Produk Baru" di tab ini
        const btnTambah = wrapper.querySelector('.btn-tambah-produk');
        if(btnTambah) btnTambah.addEventListener('click', ()=> tambahProdukBaris(wrapper));

        // Tombol "Tambah ke Ringkasan" pada tiap baris
        wrapper.querySelectorAll('.btn-tambah-ke-ringkasan').forEach(btn=>
            btn.addEventListener('click', ()=> tambahKeRingkasan(btn.closest('.produk-item')))
        );
    }

    function attachProductSearch(wrapper){
        const input  = wrapper.querySelector('.product-search');
        const container = wrapper.querySelector('.product-container');
        const list   = wrapper.querySelector('.dropdown-list');

        input.addEventListener('input', async e=>{
            const q = input.value.trim(); if(q.length<2){list.style.display='none'; return;}
            try{
                const res = await fetch(`/products/search?q=${encodeURIComponent(q)}`);
                const data = await res.json();
                list.innerHTML='';
                data.forEach(p=>{
                    const opt=document.createElement('div'); opt.className='dropdown-option';
                    opt.textContent=`${p.name} - Rp${p.price}`;
                    opt.dataset.id=p.id; opt.dataset.type=p.type; opt.dataset.price=p.price; opt.dataset.name=p.name;
                    list.appendChild(opt);
                });
                list.style.display=data.length?'block':'none';
            }catch(err){console.error(err);}
        });

        list.addEventListener('click', e=>{
            if(e.target.classList.contains('dropdown-option')){
                input.value = e.target.dataset.name;
                wrapper.querySelector('.product_id').value   = e.target.dataset.id;
                wrapper.querySelector('.product_type').value = e.target.dataset.type;
                wrapper.querySelector('.product_name').value = e.target.dataset.name;
                wrapper.querySelector('.price').value        = e.target.dataset.price;
                list.style.display='none';
            }
        });

        document.addEventListener('click', e=>{
            if(!container.contains(e.target)) list.style.display='none';
        });
    }

    function tambahProdukBaris(tabWrapper){
        const produkArea = tabWrapper.querySelector('.produk-area');
        const clone = tabWrapper.querySelector('.produk-item').cloneNode(true);
        clone.querySelectorAll('input').forEach(inp=>inp.value='');
        clone.querySelectorAll('select').forEach(sel=>sel.selectedIndex=0);
        produkArea.appendChild(clone);
        attachProductSearch(clone);
        clone.querySelector('.btn-tambah-ke-ringkasan').addEventListener('click', ()=>
            tambahKeRingkasan(clone)
        );
    }

    function tambahKeRingkasan(wrapper){
        const ref   = document.querySelector('.tab-content.active .ref_no').value;
        const client= document.querySelector('.tab-content.active .client_name').value;
        const name  = wrapper.querySelector('.product_name').value;
        const qty   = parseInt(wrapper.querySelector('.qty').value)||0;
        const price = parseInt(wrapper.querySelector('.price').value)||0;
        const staff = wrapper.querySelector('.staff_nik').selectedOptions[0]?.text||'-';
        const loc   = wrapper.querySelector('.location').value;
        const date  = wrapper.querySelector('.scheduled_date').value;
        const time  = wrapper.querySelector('.scheduled_time').value;
        const sub   = qty*price;
        if(!name || !price){ alert('Lengkapi produk dan harga'); return; }

        const tbody = document.getElementById('sum-body');
        if(tbody.querySelector('.muted')) tbody.innerHTML='';

        const tr=document.createElement('tr');
        tr.innerHTML=`
            <td>${ref}</td>
            <td>${client}</td>
            <td>${name}</td>
            <td>${qty}</td>
            <td>Rp. ${price.toLocaleString('id-ID')}</td>
            <td>Rp. ${sub.toLocaleString('id-ID')}</td>
            <td>${staff.split(' (')[0]}</td>
            <td>${loc}</td>
            <td>${date}</td>
            <td>${time}</td>
            <td><button type="button" class="btn-hapus-produk btn btn-sm btn-outline-danger">Hapus</button></td>
        `;
        tbody.appendChild(tr);
        tr.querySelector('.btn-hapus-produk').addEventListener('click', ()=>{ tr.remove(); hitungTotal(); });
        hitungTotal();
    }

    function hitungTotal(){
        let tot=0;
        document.querySelectorAll('#sum-body tr').forEach(tr=>{
            const sub = parseInt(tr.cells[5].textContent.replace(/[^\d]/g,''))||0;
            tot+=sub;
        });
        document.getElementById('sum-total').textContent='Rp. '+tot.toLocaleString('id-ID');
    }

    // ---------- Inisialisasi halaman ----------
    initTabForm(document.querySelector('.tab-content.active'));
});