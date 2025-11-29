@extends('layout.main')

@section('title', 'Master Data Role')
@section('page_title', 'Master Data Role')

@section('table')
{{-- Tombol Create --}}
<div class="top-actions">
    <a href="{{ route('master.role.create') }}" class="btn-create">+ Tambah Role</a>
</div>

  <table>
  <thead>
    <tr>
      <th>ID Role</th>
      <th>Nama Role</th>
      <th>Aksi</th>
    </tr>
  </thead>

  <tbody>
    @forelse($rows as $r)
      <tr>
        <td>{{ $r['idrole'] }}</td>
        <td>{{ $r['nama_role'] }}</td>
        <td>
            <a href="{{ route('master.role.edit', $r['idrole']) }}" class="btn edit">Edit</a>

            <form action="{{ route('master.role.delete', $r['idrole']) }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn delete" onclick="return confirm('Yakin ingin menghapus Role ini?')">Hapus</button>
            </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="3">Belum ada data.</td></tr>
    @endforelse
  </tbody>
</table>

@endsection
