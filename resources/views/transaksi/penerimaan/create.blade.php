@extends('layout.transaksi')

@section('title', 'Buat Penerimaan Baru')
@section('page_title', 'Form Penerimaan')

@section('table')



@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- STEP 1: Pilih pengadaan --}}
<div style="margin-bottom:20px;">
    <label>Pengadaan</label>
    <select name="idpengadaan_select"
            onchange="if(this.value){ window.location='{{ route('penerimaan.create') }}?idpengadaan='+this.value }">
        <option value="">Pilih Pengadaan</option>
        @foreach($pengadaanList as $p)
            <option value="{{ $p->idpengadaan }}"
                {{ ($selectedId == $p->idpengadaan) ? 'selected' : '' }}>
                {{ $p->idpengadaan }}. {{ $p->nama_vendor }}
            </option>
        @endforeach
    </select>
</div>

<div style="margin-bottom: 15px;">
    <a href="{{ route('penerimaan.index') }}" 
       style="
            background:#6c757d;
            color:white;
            padding:6px 12px;
            border-radius:6px;
            text-decoration:none;
       ">
        Kembali
    </a>
</div>


@if(!$selectedId)
    <p><i>Silakan pilih pengadaan terlebih dahulu.</i></p>
@elseif(!$selectedPengadaan)
    <p><i>Data pengadaan tidak ditemukan.</i></p>
@else

    <table style="margin-bottom:20px;">
        <tr>
            <th style="width:180px;">ID Pengadaan</th>
            <td>{{ $selectedPengadaan->idpengadaan }}</td>
        </tr>
        <tr>
            <th>Vendor</th>
            <td>{{ $selectedPengadaan->nama_vendor }}</td>
        </tr>
        <tr>
            <th>User Pengadaan</th>
            <td>{{ $selectedPengadaan->username }}</td>
        </tr>
        <tr>
            <th>Timestamp</th>
            <td>{{ $selectedPengadaan->timestamp }}</td>
        </tr>
        <tr>
            <th>Status Pengadaan</th>
            <td>{{ $selectedPengadaan->status }}</td>
        </tr>
    </table>

    @if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 10px;">
        <ul style="margin:0; padding-left: 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- FORM PENERIMAAN --}}
    <form action="{{ route('penerimaan.store') }}" method="POST">
        @csrf

        <input type="hidden" name="idpengadaan" value="{{ $selectedPengadaan->idpengadaan }}">

        <table border="1" width="100%" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Jumlah Dipesan</th>
                <th>Sudah Diterima</th>
                <th>Stok</th>
                <th>Jumlah Terima (sekarang)</th>
                <th>Harga Satuan Terima</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailPengadaan as $i => $d)
                <tr>
                    <td>
                        {{ $d->nama_barang }} ({{ $d->jenis }})
                        <input type="hidden" name="items[{{ $i }}][idbarang]" value="{{ $d->idbarang }}">
                    </td>
                    <td>{{ $d->total_pesan }}</td>
                    <td>{{ $d->total_terima }}</td>
                    <td>{{ $d->sisa }}</td>

                    <td>
                        <input type="number"
                            name="items[{{ $i }}][jumlah_terima]"
                            min="0"
                            max="{{ $d->sisa }}"
                            value="{{ $d->sisa }}"
                            required>
                    </td>
                    <td>{{ $d->harga_satuan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <i>Tidak ada barang yang perlu diterima lagi untuk pengadaan ini.</i>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>


        <br>

        <button type="submit" class="background:#0d6efd; padding:6px 12px; border-radius:6px; text-decoration:none;">Simpan Penerimaan</button>
    </form>

@endif

@endsection
