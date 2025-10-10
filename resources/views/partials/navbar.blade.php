<nav class="nav">
  <div class="nav-left">
    <a class="nav-brand" href="/draft">Draft Transaksi</a>
    <a class="nav-link" href="/list">List Transaksi</a>
    <a class="nav-link" href="/buat">Buat Transaksi</a>

    <div class="nav-dropdown">
  <button class="nav-link dropdown-toggle" data-dd>Produk ▾</button>
  <div class="dropdown-menu">
    <a href="/produk/service" class="dropdown-item">Service</a>
    <a href="/produk/obat" class="dropdown-item">Obat</a>
  </div>
</div>
  </div>

  <div class="nav-right">
    <div class="nav-dropdown">
      <button class="nav-user" data-dd>
        <span class="user-name">{{ auth()->user()->username ?? 'Admin' }}</span> ▾
      </button>
      <div class="dropdown-menu dropdown-right">
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
          @csrf
          <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
            Logout
          </button>
        </form>
      </div>
    </div>
  </div>
</nav>
