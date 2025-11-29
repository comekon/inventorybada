@extends('layout.transaksi')

@section('title', 'Detail Pengadaan')
@section('page_title', 'Detail Pengadaan')

@section('table')

<div class="top-actions">
    <a href="{{ route('pengadaan.index') }}" class="btn-create" style="background:#6c757d; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
        Kembali
    </a>
</div>

{{-- HEADER PENGADAAN --}}
<table style="margin-bottom:20px;">
    <tr>
        <th style="width:150px;">ID Pengadaan</th>
        <td>{{ $header->idpengadaan }}</td>
    </tr>
    <tr>
        <th>Timestamp</th>
        <td>{{ $header->timestamp }}</td>
    </tr>
    <tr>
        <th>Vendor</th>
        <td>{{ $header->nama_vendor }}</td>
    </tr>
    <tr>
        <th>User</th>
        <td>{{ $header->username }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            @if($header->status === 'P')
                <span class="badge badge-warning">Proses</span>
            @elseif($header->status === 'S')
                <span class="badge badge-success">Selesai</span>
            @else
                {{ $header->status }}
            @endif
        </td>
    </tr>
    <tr>
        <th>Subtotal</th>
        <td>{{ number_format($header->subtotal_nilai, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>PPN</th>
        <td>{{ number_format($header->ppn, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>Total</th>
        <td>{{ number_format($header->total_nilai, 0, ',', '.') }}</td>
    </tr>
</table>

{{-- DETAIL BARANG --}}
<h3>Detail Barang</h3>

<table>
    <thead>
        <tr>
            <th>ID Detail</th>
            <th>Barang</th>
            <th>Jenis</th>
            <th>Harga Satuan</th>
            <th>Jumlah</th>
            <th>Sub Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($details as $d)
            <tr>
                <td>{{ $d->iddetail_pengadaan }}</td>
                <td>{{ $d->nama_barang }}</td>
                <td>{{ $d->jenis }}</td>
                <td>{{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                <td>{{ $d->jumlah }}</td>
                <td>{{ number_format($d->sub_total, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="6">Belum ada detail untuk pengadaan ini.</td></tr>
        @endforelse
    </tbody>
</table>

@endsection
