<nav class="nav">
  <div class="nav-left">
    <a class="nav-brand" href="{{ route('draft.page') }}">Draft Transaksi</a>
    <a class="nav-link" href="{{ route('list.page') }}">List Transaksi</a>
    <a class="nav-link" href="{{ route('buat.page') }}">Buat Transaksi</a>

    {{-- Dropdown Produk --}}
    <div class="nav-dropdown">
      <button class="nav-link dropdown-toggle" data-dd>Produk ▾</button>
      <div class="dropdown-menu">
        <a href="{{ route('service.index') }}" class="dropdown-item">Service</a>
        <a href="{{ route('med.index') }}" class="dropdown-item">Obat</a>
      </div>
    </div>

    {{-- Hanya admin yang bisa melihat --}}
    @if(auth()->user()->role === 'admin')
      <a href="{{ route('staff.index') }}" class="nav-link">Staff Management</a>
      <a href="{{ route('user.index') }}" class="nav-link">User Management</a>
    @endif
  </div>

  <div class="nav-right">
    <div class="nav-dropdown">
      <button class="nav-user" data-dd>
        <span class="user-name">{{ auth()->user()->username ?? 'Admin' }}</span> ▾
      </button>
      <div class="dropdown-menu dropdown-right">
        {{-- Tombol Ganti Password --}}
        <button type="button" class="dropdown-item" onclick="document.getElementById('changePassModal').style.display='block'">
          Ganti Password
        </button>

        {{-- Tombol Logout --}}
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
          @csrf
          <button type="submit" class="dropdown-item">Logout</button>
        </form>
      </div>
    </div>
  </div>
</nav>

{{-- Modal Ganti Password --}}
<div id="changePassModal" class="modal" style="display:none;">
  <div class="modal-content" style="max-width:400px; margin:auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.2);">
    <h3>Ganti Password</h3>
    <form action="{{ route('password.update') }}" method="POST">
      @csrf
      <div class="form-row">
        <label>Password Lama</label>
        <input type="password" name="old_password" class="input" required>
      </div>
      <div class="form-row">
        <label>Password Baru</label>
        <input type="password" name="new_password" class="input" required>
      </div>
      <div class="form-row">
        <label>Konfirmasi Password Baru</label>
        <input type="password" name="new_password_confirmation" class="input" required>
      </div>
      <div class="form-actions" style="margin-top:10px; display:flex; justify-content:space-between;">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('changePassModal').style.display='none'">Batal</button>
      </div>
    </form>
  </div>
</div>

{{-- Tambahkan sedikit CSS --}}
<style>
.modal {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.4);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 999;
}
.modal-content {
  animation: fadeIn 0.2s ease;
}
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}
</style>
