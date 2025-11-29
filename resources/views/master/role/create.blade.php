@extends('layout.main')

@section('title', 'Tambah Role')
@section('page_title', 'Tambah Role')

@section('table')

<form action="{{ route('master.role.store') }}" method="POST">
    @csrf

    <label>Nama Role:</label>
    <input type="text" name="nama_role" required>

    <button type="submit" class="btn-create">Simpan</button>
</form>

@endsection
