@extends('layout.transaksi')

@section('title', 'Data Penjualan')
@section('page_title', 'Data Penjualan')

@section('table')

<div class="top-actions">
    <a href="{{ route('penjualan.create') }}" class="btn-create">
        + Tambah Penjualan
    </a>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table>
    <thead>
        <tr>
            <th>ID Penjualan</th>
            <th>Tanggal</th>
            <th>User</th>
            <th>Margin (%)</th>
            <th>Subtotal (Rp)</th>
            <th>PPN (Rp)</th>
            <th>Total (Rp)</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $r)
            <tr>
                <td>{{ $r->idpenjualan }}</td>
                <td>{{ $r->created_at }}</td>
                <td>{{ $r->username }}</td>
                <td>{{ $r->margin_persen }}</td>
                <td>{{ number_format($r->subtotal_nilai, 0, ',', '.') }}</td>
                <td>{{ number_format($r->ppn, 0, ',', '.') }}</td>
                <td>{{ number_format($r->total_nilai, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('penjualan.show', $r->idpenjualan) }}" class="btn btn-sm btn-primary">
                        Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8"><i>Belum ada data penjualan.</i></td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection
