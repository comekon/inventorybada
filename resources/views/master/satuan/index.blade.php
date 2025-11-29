@extends('layout.main')

@section('title', 'Master Data Satuan')
@section('page_title', 'Master Data Satuan')

@section('table')
<div class="top-actions">
    <a href="{{ route('master.satuan.create') }}" class="btn-create">+ Tambah Satuan</a>
</div>

  <div class="filter-bar">
    <span class="label">Filter status:</span>
    <a href="{{ route('master.satuan.index', ['status' => 'all']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'all' ? 'active' : '' }}">
       Semua
    </a>
    <a href="{{ route('master.satuan.index', ['status' => 'aktif']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'aktif' ? 'active' : '' }}">
       Aktif
    </a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama Satuan</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
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
          <td>
            <a href="{{ route('master.satuan.edit', $r['idsatuan']) }}" class="btn edit">Edit</a>

            <form action="{{ route('master.satuan.delete', $r['idsatuan']) }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn delete" onclick="return confirm('Yakin ingin menghapus satuan ini?')">Hapus</button>
            </form>
        </td>
        </tr>
      @empty
        <tr><td colspan="3">Belum ada data.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
