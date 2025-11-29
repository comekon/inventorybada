@extends('layout.main')

@section('title', 'Edit Vendor')
@section('page_title', 'Edit Vendor')

@section('table')

<form method="POST" action="{{ route('master.vendor.update', $row->idvendor) }}">
  @csrf

  <label>Nama Vendor</label>
  <input type="text" name="nama_vendor" value="{{ $row->nama_vendor }}" required>

  <label>Badan Hukum</label>
  <select name="badan_hukum" required>
      <option value="Y" {{ $row->badan_hukum == 'Y' ? 'selected' : '' }}>Badan Hukum</option>
      <option value="T" {{ $row->badan_hukum == 'T' ? 'selected' : '' }}>Non Badan Hukum</option>
  </select>

  <label>Status</label>
  <select name="status" required>
      <option value="1" {{ $row->status == '1' ? 'selected' : '' }}>Aktif</option>
      <option value="0" {{ $row->status == '0' ? 'selected' : '' }}>Nonaktif</option>
  </select>

  <button class="btn btn-primary">Update</button>
  <a href="{{ route('master.vendor.index') }}" class="btn btn-secondary">Kembali</a>
</form>

@endsection
