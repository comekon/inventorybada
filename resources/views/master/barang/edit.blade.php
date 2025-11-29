@extends('layout.main')

@section('title', 'Edit Barang')
@section('page_title', 'Edit Barang')

@section('table')

<form action="{{ route('master.barang.update', $barang->idbarang) }}" method="POST">
    @csrf

    <label>Jenis:</label>
    <input type="text" name="jenis" value="{{ $barang->jenis }}" required>

    <label>Nama:</label>
    <input type="text" name="nama" value="{{ $barang->nama }}" required>

    <label>Satuan:</label>
    <select name="idsatuan">
        @foreach($satuan as $s)
            <option value="{{ $s->idsatuan }}" 
                {{ $barang->idsatuan == $s->idsatuan ? 'selected' : '' }}>
                {{ $s->nama_satuan }}
            </option>
        @endforeach
    </select>

    <label>Harga:</label>
    <input type="number" name="harga" value="{{ $barang->harga }}" required>

    <label>Status:</label>
    <select name="status">
        <option value="1" {{ $barang->status == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ $barang->status == 0 ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <button type="submit">Update</button>
</form>

@endsection
