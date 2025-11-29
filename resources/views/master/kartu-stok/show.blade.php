@extends('layout.transaksi')

@section('title', 'Detail Kartu Stok')
@section('page_title', 'Kartu Stok: '.$barang->nama)

@section('table')

<a href="{{ route('master.kartu-stok.index') }}" 
   class="btn btn-secondary" style="margin-bottom:15px;">Kembali</a>

<table>
  <thead>
    <tr>
      <th>Waktu</th>
      <th>Jenis</th>
      <th>Masuk</th>
      <th>Keluar</th>
      <th>Stock Akhir</th>
    </tr>
  </thead>

  <tbody>
    @forelse($mutasi as $m)
      <tr>
        <td>{{ $m->created_at }}</td>
        <td>
            @if($m->jenis_transaksi === 'A')
                Penerimaan
            @elseif($m->jenis_transaksi === 'J')
                Penjualan
            @else
                {{ $m->jenis_transaksi }}
            @endif
        </td>
        <td>{{ $m->masuk }}</td>
        <td>{{ $m->keluar }}</td>
        <td>{{ $m->stock_setelah }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="5" style="text-align:center;">
            Belum ada pergerakan stok.
        </td>
      </tr>
    @endforelse
  </tbody>
</table>

@endsection
