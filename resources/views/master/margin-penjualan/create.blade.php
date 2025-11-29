@extends('layout.main')

@section('title', 'Tambah Margin Penjualan')
@section('page_title', 'Tambah Margin Penjualan')

@section('table')

<div class="top-actions">
    <a href="{{ route('master.margin.index') }}" class="btn-create" style="background:#6c757d; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
     Kembali
    </a>
</div>

<form action="{{ route('master.margin.store') }}" method="POST">
    @csrf

    <label>Persen Margin:</label>
    <input type="number" name="persen" required>

    <label>Status:</label>
    <select name="status">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
    </select>

    <button type="submit" class="btn-create">Simpan</button>
</form>

@endsection
