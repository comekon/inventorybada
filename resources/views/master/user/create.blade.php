@extends('layout.main')

@section('title', 'Tambah User')
@section('page_title', 'Tambah User')

@section('table')

<div style="margin-bottom: 15px;">
    <a href="{{ route('master.user.index') }}"
       style="background:#6c757d; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
        Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom:10px;">
        <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('master.user.store') }}" method="POST">
    @csrf

    <table>
        <tr>
            <th style="width:180px;">Username</th>
            <td>
                <input type="text" name="username" value="{{ old('username') }}" required>
            </td>
        </tr>
        <tr>
            <th>Password</th>
            <td>
                <input type="password" name="password" required>
            </td>
        </tr>
        <tr>
            <th>Role</th>
            <td>
                <select name="idrole" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->idrole }}" {{ old('idrole') == $r->idrole ? 'selected' : '' }}>
                            {{ $r->nama_role }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
    </table>

    <br>
    <button type="submit" class="btn btn-primary">Simpan User</button>
</form>

@endsection
