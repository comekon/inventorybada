@extends('layout.transaksi')

@section('title', 'Data Penerimaan')
@section('page_title', 'Data Penerimaan')

@section('table')

<div class="top-actions">
    <a href="{{ route('penerimaan.create') }}" class="btn-create">
        + Tambah Penerimaan
    </a>
</div>

<table>
    <thead>
        <tr>
            <th>ID Penerimaan</th>
            <th>Tanggal</th>
            <th>ID Pengadaan</th>
            <th>Vendor</th>
            <th>User</th>
            <th>Status</th>
            <th>Total Penerimaan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $r)
            <tr>
                <td>{{ $r->idpenerimaan }}</td>
                <td>{{ $r->created_at }}</td>
                <td>{{ $r->idpengadaan_ref }}</td>
                <td>{{ $r->nama_vendor }}</td>
                <td>{{ $r->username }}</td>
                <td>
                    @php
                        $map = [
                            'S' => 'Selesai',
                            'B' => 'Sebagian',
                            'P' => 'Proses',
                            'N' => 'Baru',
                        ];
                    @endphp

                    {{ $map[$r->status] ?? $r->status }}
                </td>

                <td>{{ number_format($r->total_penerimaan, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('penerimaan.show', $r->idpenerimaan) }}" class="btn-detail">
                        Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="8">Belum ada data penerimaan.</td></tr>
        @endforelse
    </tbody>
</table>

<style>
  .btn-detail {
      padding: 4px 10px;
      border-radius: 6px;
      background: #0d6efd;
      color: #fff;
      text-decoration: none;
      font-size: 13px;
  }
  .btn-detail:hover {
      background: #0b5ed7;
  }
</style>

@endsection
