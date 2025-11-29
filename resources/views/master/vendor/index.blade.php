@extends('layout.transaksi')

@section('title', 'Master Vendor')
@section('page_title', 'Master Data Vendor')

@section('table')


<div style="margin-bottom:15px; text-align:right;">
  <a href="{{ route('master.vendor.create') }}" class="btn btn-primary">
      + Tambah Vendor
  </a>
</div>

  <div class="filter-bar">
    <span class="label">Filter status:</span>
    <a href="{{ route('master.vendor.index', ['status' => 'all']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'all' ? 'active' : '' }}">
       Semua
    </a>
    <a href="{{ route('master.vendor.index', ['status' => 'aktif']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'aktif' ? 'active' : '' }}">
       Aktif
    </a>
  </div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nama Vendor</th>
      <th>Badan Hukum</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>

  <tbody>
    @forelse($rows as $r)
      <tr>
        <td>{{ $r->idvendor }}</td>
        <td>{{ $r->nama_vendor }}</td>

        {{-- badan_hukum: Y / T --}}
        <td>
            @if($r->badan_hukum === 'Y')
                Badan Hukum
            @else
                Non Badan Hukum
            @endif
        </td>

        {{-- status: 1 / 0 --}}
        <td>
            @if($r->status === '1' || $r->status === 1)
                <span class="status-active">Aktif</span>
            @else
                <span>Nonaktif</span>
            @endif
        </td>

        <td>
          <a href="{{ route('master.vendor.edit', $r->idvendor) }}" class="btn edit">Edit</a>

          <form action="{{ route('master.vendor.delete', $r->idvendor) }}" 
                method="POST" style="display:inline;">
            @csrf
            <button class="btn delete" onclick="return confirm('Hapus vendor?')">
                Hapus
            </button>
          </form>
        </td>
      </tr>

    @empty
      <tr><td colspan="5">Belum ada data vendor.</td></tr>
    @endforelse
  </tbody>
</table>

@endsection
