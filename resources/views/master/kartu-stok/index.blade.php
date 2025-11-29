@extends('layout.transaksi')

@section('title', 'Kartu Stok')
@section('page_title', 'Daftar Kartu Stok')

@section('table')

<table>
  <thead>
    <tr>
      <th>ID Barang</th>
      <th>Nama</th>
      <th>Stok Saat Ini</th>
      <th>Aksi</th>
    </tr>
  </thead>

  <tbody>
    @forelse($rows as $r)
      <tr>
        <td>{{ $r->idbarang }}</td>
        <td>{{ $r->nama }}</td>
        <td>{{ $r->stock_akhir }}</td>
        <td>
          <a href="{{ route('master.kartu-stok.show', $r->idbarang) }}" class="btn info">
              Detail
          </a>
        </td>
      </tr>
    @empty
      <tr><td colspan="4">Belum ada data.</td></tr>
    @endforelse
  </tbody>
</table>

@endsection
