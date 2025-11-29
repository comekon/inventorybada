@extends('layout.main')

@section('title', 'Edit Satuan')
@section('page_title', 'Edit Satuan')

@section('table')

<div class="top-actions">
    <a href="{{ route('master.satuan.index') }}" class="btn-create" style="background:#6c757d">
        ← Kembali
    </a>
</div>

<form action="{{ route('master.satuan.update', $satuan->idsatuan) }}" method="POST">
    @csrf

    <label>Nama Satuan:</label>
    <input type="text" name="nama_satuan" value="{{ $satuan->nama_satuan }}" required>

    <label>Status:</label>
    <select name="status">
        <option value="1" {{ $satuan->status == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ $satuan->status == 0 ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <button type="submit" class="btn-create">Update</button>
</form>

@endsection
