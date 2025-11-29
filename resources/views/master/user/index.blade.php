@extends('layout.main')

@section('title', 'Master Data User')
@section('page_title', 'Master Data User')

@section('table')

  <div class="top-actions" style="margin-bottom:10px; text-align:right;">
      <a href="{{ route('master.user.create') }}" class="btn-create">
          + Tambah User
      </a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID User</th>
        <th>Username</th>
        <th>ID Role</th>
        <th>Nama Role</th>
        <th style="width:150px; text-align:center;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
        <tr>
          <td>{{ $r['iduser'] }}</td>
          <td>{{ $r['username'] }}</td>
          <td>{{ $r['idrole'] }}</td>
          <td>{{ $r['nama_role'] }}</td>
          <td style="text-align:center;">
              <a href="{{ route('master.user.edit', $r['iduser']) }}" class="btn edit">Edit</a>

              <form action="{{ route('master.user.delete', $r['iduser']) }}"
                    method="POST"
                    style="display:inline;">
                  @csrf
                  <button type="submit"
                          class="btn delete"
                          onclick="return confirm('Yakin ingin menghapus user ini?')">
                      Hapus
                  </button>
              </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5">Belum ada data user.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
