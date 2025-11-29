@extends('layout.main')

@section('title', 'Tambah Vendor')
@section('page_title', 'Tambah Vendor')

@section('table')

<form method="POST" action="{{ route('master.vendor.store') }}">
  @csrf

  <label>Nama Vendor</label>
  <input type="text" name="nama_vendor" required>

  <label>Badan Hukum</label>
  <select name="badan_hukum" required>
      <option value="Y">Badan Hukum</option>
      <option value="T">Non Badan Hukum</option>
  </select>

  <label>Status</label>
  <select name="status" required>
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
  </select>

  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('master.vendor.index') }}" class="btn btn-secondary">Kembali</a>
</form>

@endsection
