@extends('layout.transaksi')

@section('title', 'Detail Penjualan')
@section('page_title', 'Detail Penjualan')

@section('table')

<a href="{{ route('penjualan.index') }}" class="btn btn-secondary" style="margin-bottom:15px;">
    &larr; Kembali ke daftar
</a>

<table style="margin-bottom:20px;">
    <tr>
        <th style="width:180px;">ID Penjualan</th>
        <td>{{ $header->idpenjualan }}</td>
    </tr>
    <tr>
        <th>Tanggal</th>
        <td>{{ $header->created_at }}</td>
    </tr>
    <tr>
        <th>User</th>
        <td>{{ $header->username }}</td>
    </tr>
    <tr>
        <th>Margin (%)</th>
        <td>{{ $header->margin_persen }}</td>
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

<h3>Detail Barang</h3>

<table border="1" width="100%" cellspacing="0" cellpadding="8">
    <thead>
        <tr>
            <th>Barang</th>
            <th>Harga Satuan</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($details as $d)
            <tr>
                <td>{{ $d->nama_barang }} ({{ $d->jenis }})</td>
                <td>{{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                <td>{{ $d->jumlah }}</td>
                <td>{{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4"><i>Belum ada detail penjualan.</i></td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection
