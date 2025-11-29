@extends('layout.transaksi')

@section('title', 'Detail Penerimaan')
@section('page_title', 'Detail Penerimaan')

@section('table')

<div class="top-actions">
    <a href="{{ route('penerimaan.index') }}" class="btn-create" style="background:#6c757d; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
        Kembali
    </a>
</div>

<table style="margin-bottom:20px;">
    <tr>
        <th style="width:180px;">ID Penerimaan</th>
        <td>{{ $header->idpenerimaan }}</td>
    </tr>
    <tr>
        <th>Tanggal</th>
        <td>{{ $header->created_at }}</td>
    </tr>
    <tr>
        <th>ID Pengadaan</th>
        <td>{{ $header->idpengadaan_ref }}</td>
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
        <td>{{ $header->status }}</td>
    </tr>
    <tr>
        <th>Total Penerimaan</th>
        <td>{{ number_format($header->total_penerimaan, 0, ',', '.') }}</td>
    </tr>
</table>

<h3>Detail Barang Diterima</h3>

<table>
    <thead>
        <tr>
            <th>ID Detail</th>
            <th>Barang</th>
            <th>Jenis</th>
            <th>Jumlah Terima</th>
            <th>Harga Satuan</th>
            <th>Sub Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($details as $d)
            <tr>
                <td>{{ $d->iddetail_penerimaan }}</td>
                <td>{{ $d->nama_barang }}</td>
                <td>{{ $d->jenis }}</td>
                <td>{{ $d->jumlah_terima }}</td>
                <td>{{ number_format($d->harga_satuan_terima, 0, ',', '.') }}</td>
                <td>{{ number_format($d->sub_total_terima, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="6">Belum ada detail untuk penerimaan ini.</td></tr>
        @endforelse
    </tbody>
</table>

@endsection
