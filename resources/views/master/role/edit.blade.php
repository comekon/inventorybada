@extends('layout.main')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role')

@section('table')

<form action="{{ route('master.role.update', $role->idrole) }}" method="POST">
    @csrf

    <label>Nama Role:</label>
    <input type="text" name="nama_role" value="{{ $role->nama_role }}" required>

    <button type="submit" class="btn-create">Update</button>
</form>

@endsection
