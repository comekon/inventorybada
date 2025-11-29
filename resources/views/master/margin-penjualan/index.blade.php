@extends('layout.main')

@section('title', 'Master Data Margin Penjualan')
@section('page_title', 'Master Data Margin Penjualan')

@section('table')
    <div class="top-actions">
        <a href="{{ route('master.margin.create') }}" class="btn-create">+ Tambah Margin</a>
    </div>

  <div class="filter-bar">
    <span class="label">Filter status:</span>
    <a href="{{ route('master.margin.index', ['status' => 'all']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'all' ? 'active' : '' }}">
       Semua
    </a>
    <a href="{{ route('master.margin.index', ['status' => 'aktif']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'aktif' ? 'active' : '' }}">
       Aktif
    </a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Persen</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
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
          <td>
            <a href="{{ route('master.margin.edit', $r['idmargin_penjualan']) }}" class="btn edit">Edit</a>

            <form action="{{ route('master.margin.delete', $r['idmargin_penjualan']) }}" 
                method="POST" style="display:inline;">
                @csrf
                <button class="btn delete"
                        onclick="return confirm('Yakin ingin menghapus margin ini?')">
                    Hapus
                </button>
            </form>
        </td>
        </tr>
      @empty
        <tr><td colspan="3">Belum ada data.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
