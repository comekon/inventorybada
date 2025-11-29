@extends('layout.main')

@section('title', 'Edit Margin Penjualan')
@section('page_title', 'Edit Margin Penjualan')

@section('table')

<div class="top-actions">
    <a href="{{ route('master.margin.index') }}" class="btn-create" style="background:#6c757d; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
        Kembali
    </a>
</div>

<form action="{{ route('master.margin.update', $margin->idmargin_penjualan) }}" method="POST">
    @csrf

    <label>Persen Margin:</label>
    <input type="number" name="persen" value="{{ $margin->persen }}" required>

    <label>Status:</label>
    <select name="status">
        <option value="1" {{ $margin->status == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ $margin->status == 0 ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <button type="submit" class="btn-create">Update</button>
</form>

@endsection
