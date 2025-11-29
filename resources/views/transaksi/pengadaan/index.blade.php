@extends('layout.transaksi')

@section('title', 'Data Pengadaan')
@section('page_title', 'Data Pengadaan')

@section('table')

{{-- bagian notif --}}
@if(session('success'))
    <div class="toast-success" id="toast-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="toast-error" id="toast-error">
        {{ session('error') }}
    </div>
@endif

<div class="top-actions">
    <a href="{{ route('pengadaan.create') }}" class="btn-create">
        + Tambah Pengadaan
    </a>
</div>

{{-- 🔹 Filter status --}}
<div class="filter-bar" style="margin-bottom: 12px;">
    <span class="label">Filter status:</span>

    <a href="{{ route('pengadaan.index', ['status' => 'all']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'all' ? 'active' : '' }}">
        Semua
    </a>

    <a href="{{ route('pengadaan.index', ['status' => 'proses']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'proses' ? 'active' : '' }}">
        Proses
    </a>

    <a href="{{ route('pengadaan.index', ['status' => 'selesai']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'selesai' ? 'active' : '' }}">
        Selesai
    </a>

    <a href="{{ route('pengadaan.index', ['status' => 'sebagian']) }}"
       class="filter-btn {{ ($statusFilter ?? 'all') === 'sebagian' ? 'active' : '' }}">
        Sebagian Selesai
    </a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Timestamp</th>
            <th>Vendor</th>
            <th>User</th>
            <th>Status</th>
            <th>Subtotal</th>
            <th>PPN</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $r)
            <tr>
                <td>{{ $r->idpengadaan }}</td>
                <td>{{ $r->timestamp }}</td>
                <td>{{ $r->nama_vendor }}</td>
                <td>{{ $r->username }}</td>
                <td>
                    @if($r->status === 'P')
                        <span class="badge badge-warning">Proses</span>
                    @elseif($r->status === 'S')
                        <span class="badge badge-success">Selesai</span>
                    @else ($r->status === 'B')
                        <span class="badge badge-warning">Selesai Sebagian</span>
                    @endif
                </td>
                <td>{{ number_format($r->subtotal_nilai, 0, ',', '.') }}</td>
                <td>{{ number_format($r->ppn, 0, ',', '.') }}</td>
                <td>{{ number_format($r->total_nilai, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('pengadaan.show', $r->idpengadaan) }}" class="btn-detail">
                        Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="8">Belum ada data pengadaan.</td></tr>
        @endforelse
    </tbody>
</table>

@endsection
