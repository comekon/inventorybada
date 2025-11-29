@extends('layout.main')

@section('title', 'Master Data Barang')
@section('page_title', 'Master Data Barang')

@section('table')
    <div class="top-actions">
        <a href="{{ route('master.barang.create') }}" class="btn-create">+ Tambah Barang</a>
    </div>
  <div class="filter-bar">
    <span class="label">Filter status:</span>
    <a href="{{ route('master.barang.index', ['status' => 'all']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'all' ? 'active' : '' }}">
       Semua
    </a>
    <a href="{{ route('master.barang.index', ['status' => 'aktif']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'aktif' ? 'active' : '' }}">
       Aktif
    </a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Jenis</th>
        <th>Nama</th>
        <th>Satuan</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
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
          <td>
            <a href="{{ route('master.barang.edit', $r['idbarang']) }}" class="btn edit">Edit</a>

            <form action="{{ route('master.barang.delete', $r['idbarang']) }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn delete" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
            </form>
          </td>

        </tr>
      @empty
        <tr><td colspan="6">Belum ada data.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
