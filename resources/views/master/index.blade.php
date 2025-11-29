<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Master Data</title>
  <style>
    /* Base */
    body { font-family: Arial, sans-serif; margin: 0; background: #f9f9f9; }
    h1 { color: #333; }
    .nav { display: flex; gap: 12px; margin: 10px 0 20px; }
    .nav a { text-decoration: none; padding: 8px 12px; border-radius: 6px; background: white; color: #0d6efd; border: 1px solid #dbe1f1; }
    .nav a.active { background: #0d6efd; color: #fff; border-color: #0d6efd; }

    /* Layout */
    .hamburger {
    position: fixed;
    left: 12px;
    top: 12px;
    z-index: 1100;
    background: #0d6efd;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 10px;
    font-size: 18px;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    /* .sidebar { position: fixed; z-index: 1050; left: 0; top: 0; bottom: 0; width: 240px; background: #fff; border-right: 1px solid #eaeaea; padding: 16px; overflow-y: auto; transform: translateX(-100%); transition: transform .2s ease; }
    .sidebar.open { transform: translateX(0); } */
    .sidebar {
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    bottom: 0;
    width: 240px;
    background: #fff;
    border-right: 1px solid #eaeaea;
    padding: 16px;
    overflow-y: auto;

    /* 🔥 hapus transform & transition */
    transform: none !important;}

    .content {
    padding: 20px;
    margin-left: 260px; /* 🔥 jarak aman */}

    /* @media (min-width: 992px) { .sidebar-open .hamburger { left: 256px; } .sidebar-open .content { margin-left: 260px; } } */

    /* Sidebar items */
    .sidebar .brand { font-weight: bold; color: #0d6efd; margin-bottom: 12px; }
    .menu-group { margin-top: 8px; }
    .menu-title { font-size: 12px; color: #888; margin: 12px 0 8px; text-transform: uppercase; letter-spacing: .6px; }
    .menu-item { display: block; padding: 8px 10px; border-radius: 6px; text-decoration: none; color: #333; }
    .menu-item:hover { background: #f5f8ff; color: #0d6efd; }
    .menu-item.active { background: #0d6efd; color: #fff; }

    .summary { display: flex; gap: 20px; margin: 20px 0; }
    .card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); flex: 1; }
    .card h3 { margin: 0; font-size: 14px; color: #777; }
    .card p { font-size: 18px; font-weight: bold; margin: 5px 0 0; }

    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; border-radius: 8px; overflow: hidden; }
    th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
    th { background: #f0f0f0; }
    .status-active { background: #0d6efd; color: white; padding: 5px 10px; border-radius: 12px; font-size: 12px; }
    .actions { display: flex; gap: 10px; }
    .btn { border: none; cursor: pointer; background: none; }
    .btn.edit { color: #0d6efd; }
    .btn.delete { color: red; }
    .search-bar { margin-top: 10px; }
    input[type="text"] { padding: 8px; width: 250px; border-radius: 5px; border: 1px solid #ccc; }
    .add-btn { background: #0d6efd; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; margin-left: 10px; }
  </style>
</head>
<body>

  <!-- Sidebar toggle + sidebar -->
  <!-- <button class="hamburger" id="sidebarToggle" aria-label="Buka/Tutup menu">☰</button> -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">Menu</div>
    @php $tabs = ['role' => 'Role', 'user' => 'User', 'barang' => 'Barang', 'satuan' => 'Satuan', 'margin' => 'Margin Penjualan']; @endphp
    <div class="menu-group">
      <div class="menu-title">Master</div>
      @foreach($tabs as $key => $label)
        <a class="menu-item {{ $tab === $key ? 'active' : '' }}" href="{{ route('master.index', ['tab' => $key]) }}">{{ $label }}</a>
      @endforeach
    </div>
    <div class="menu-group">
      <div class="menu-title">Transaksi</div>
      <a class="menu-item" href="{{ url('/transaksi/penjualan') }}">Penjualan</a>
      <a class="menu-item" href="{{ url('/transaksi/pengadaan') }}">Pengadaan</a>
      <a class="menu-item" href="{{ url('/transaksi/penerimaan') }}">Penerimaan</a>
    </div>
  </aside>

  <div class="content">
    <h1>Master Data</h1>

    <!-- Summary cards -->
    <div class="summary">
      <div class="card">
        <h3>Total Role</h3>
        <p>{{ $summary['role'] }}</p>
      </div>
      <div class="card">
        <h3>Total User</h3>
        <p>{{ $summary['user'] }}</p> 
      </div>
      <div class="card">
        <h3>Total Barang</h3>
        <p>{{ $summary['barang'] }}</p>
      </div>
      <div class="card">
        <h3>Total Satuan</h3>
        <p>{{ $summary['satuan'] }}</p>
      </div>
      <div class="card">
        <h3>Total Margin Penjualan</h3>
        <p>{{ $summary['margin'] ?? 0 }}</p>
      </div>
    </div>
  <!-- Tabel dinamis by tab -->
    @if($tab === 'role')
    <table>
      <thead>
        <tr><th>ID Role</th><th>Nama Role</th></tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td>{{ $r['idrole'] }}</td>
            <td>{{ $r['nama_role'] }}</td>
          </tr>
        @empty
          <tr><td colspan="2">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>

    @elseif($tab === 'user')
    <table>
      <thead>
        <tr><th>ID User</th><th>Username</th><th>ID Role</th><th>Nama Role</th></tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td>{{ $r['iduser'] }}</td>
            <td>{{ $r['username'] }}</td>
            <td>{{ $r['idrole'] }}</td>
            <td>{{ $r['nama_role'] }}</td>
          </tr>
        @empty
          <tr><td colspan="4">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>

    @elseif($tab === 'barang')
    <table>
      <thead>
        <tr><th>ID</th><th>Jenis</th><th>Nama</th><th>Satuan</th><th>Harga</th><th>Status</th></tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td>{{ $r['idbarang'] }}</td>
            <td>{{ $r['jenis'] }}</td>
            <td>{{ $r['nama'] }}</td>
            <td>{{ $r['nama_satuan'] }}</td>
            <td>{{ number_format($r['harga'], 0, ',', '.') }}</td>
            <td>
              @if(isset($r['status']) && (string)$r['status'] === '1')
                <span class="status-active">Aktif</span>
              @else
                <span>Nonaktif</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="6">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>

    @elseif($tab === 'satuan')
    <table>
      <thead>
        <tr><th>ID</th><th>Nama Satuan</th><th>Status</th></tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td>{{ $r['idsatuan'] }}</td>
            <td>{{ $r['nama_satuan'] }}</td>
            <td>
              @if((string)$r['status'] === '1')
                <span class="status-active">Aktif</span>
              @else
                <span>Nonaktif</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="3">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>

    @elseif($tab === 'margin')
    <table>
      <thead>
        <tr><th>ID</th><th>Persen</th><th>Status</th></tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td>{{ $r['idmargin_penjualan'] }}</td>
            <td>{{ $r['persen'] }}</td>
            <td>
              @if((string)$r['status'] === '1')
                <span class="status-active">Aktif</span>
              @else
                <span>Nonaktif</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="3">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
    @endif

  </div>

  <!-- <script>
    (function() {
      var btn = document.getElementById('sidebarToggle');
      var sidebar = document.getElementById('sidebar');
      function setBodyOpen(open) {
        if (open) { document.body.classList.add('sidebar-open'); }
        else { document.body.classList.remove('sidebar-open'); }
      }
      if (btn && sidebar) {
        // Default: buka di desktop
        // if (window.innerWidth >= 992) {
        //   sidebar.classList.add('open');
        //   setBodyOpen(true);
        // }
        btn.addEventListener('click', function() {
          var isOpen = sidebar.classList.toggle('open');
          setBodyOpen(isOpen);
        });
        // Tutup sidebar saat klik link (mobile UX)
        sidebar.addEventListener('click', function(e) {
          var target = e.target;
          if (target && target.classList && target.classList.contains('menu-item')) {
            sidebar.classList.remove('open');
            setBodyOpen(false);
          }
        });
      }
    })();
  </script> -->

</body>
</html>
