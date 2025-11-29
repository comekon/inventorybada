@extends('layout.main')

@section('title', 'Tambah Satuan')
@section('page_title', 'Tambah Satuan')

@section('table')

<div class="top-actions">
    <a href="{{ route('master.satuan.index') }}" class="btn-create" style="background:#6c757d">
        ← Kembali
    </a>
</div>

<form action="{{ route('master.satuan.store') }}" method="POST">
    @csrf

    <label>Nama Satuan:</label>
    <input type="text" name="nama_satuan" required>

    <label>Status:</label>
    <select name="status">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
    </select>

    <button type="submit" class="btn-create">Simpan</button>
</form>

@endsection
