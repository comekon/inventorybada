@extends('layout.main')

@section('title', 'Tambah Barang')
@section('page_title', 'Tambah Barang')

@section('table')

<form action="{{ route('master.barang.store') }}" method="POST">
    @csrf

    <label>Jenis:</label>
    <input type="text" name="jenis" required>

    <label>Nama:</label>
    <input type="text" name="nama" required>

    <label>Satuan:</label>
    <select name="idsatuan">
        @foreach($satuan as $s)
            <option value="{{ $s['idsatuan'] }}">{{ $s['nama_satuan'] }}</option>
        @endforeach
    </select>

    <label>Harga:</label>
    <input type="number" name="harga" required>

    <label>Status:</label>
    <select name="status">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
    </select>

    <button type="submit">Simpan</button>
</form>

@endsection
