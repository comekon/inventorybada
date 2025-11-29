<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Master Data')</title>
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
      transform: none !important;
    }

    .content {
      padding: 20px;
      margin-left: 260px; /* jarak aman */
    }

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

  <aside class="sidebar" id="sidebar">
    <div class="brand">Menu</div>

    <div class="menu-group">
      <div class="menu-title">Master</div>
      <a class="menu-item {{ request()->routeIs('master.role') ? 'active' : '' }}"
         href="{{ route('master.role.index') }}">Role</a>

      <a class="menu-item {{ request()->routeIs('master.user') ? 'active' : '' }}"
         href="{{ route('master.user.index') }}">User</a>

      <a class="menu-item {{ request()->routeIs('master.vendor') ? 'active' : '' }}"
         href="{{ route('master.vendor.index') }}">Vendor</a>

      <a class="menu-item {{ request()->routeIs('master.barang') ? 'active' : '' }}"
         href="{{ route('master.barang.index') }}">Barang</a>

      <a class="menu-item {{ request()->routeIs('master.satuan') ? 'active' : '' }}"
         href="{{ route('master.satuan.index') }}">Satuan</a>

      <a class="menu-item {{ request()->routeIs('master.margin-penjualan') ? 'active' : '' }}"
         href="{{ route('master.margin.index') }}">Margin Penjualan</a>

      <a class="menu-item {{ request()->routeIs('master.kartu-stok') ? 'active' : '' }}"
         href="{{ route('master.kartu-stok.index') }}">Kartu Stok</a>
    </div>


    <div class="menu-group">
      <div class="menu-title">Transaksi</div>
      <a class="menu-item" href="{{ url('/transaksi/penjualan') }}">Penjualan</a>
      <a class="menu-item" href="{{ url('/transaksi/pengadaan') }}">Pengadaan</a>
      <a class="menu-item" href="{{ url('/transaksi/penerimaan') }}">Penerimaan</a>
    </div>
  </aside>

  <div class="content">
    <h1>@yield('page_title', 'Master Data')</h1>

    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- TABEL / KONTEN KHUSUS HALAMAN --}}
    @yield('table')

  </div>
</body>
</html>
