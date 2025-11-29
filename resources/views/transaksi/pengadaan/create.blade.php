@extends('layout.transaksi')

@section('title', 'Buat Pengadaan Baru')
@section('page_title', 'Form Pengadaan')


@section('table')


<style>
  .add-row-btn {
      background: #0d6efd;
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      margin-bottom: 10px;
      display: inline-block;
  }
  .remove-row-btn {
      background: #dc3545;
      color: white;
      padding: 4px 8px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 12px;
  }
  table input, table select {
      width: 100%;
      padding: 5px;
  }
</style>

@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('pengadaan.store') }}" method="POST">
  @csrf

  <div style="margin-bottom:20px;">
    <label>Vendor</label>
    <select name="idvendor" required>
        <option value="">Pilih Vendor</option>
        @foreach($vendor as $v)
            <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
        @endforeach
    </select>

    {{-- 🔥 status diset otomatis PROSES (P) --}}
    <input type="hidden" name="status" value="P">
  </div>

  <div style="
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:15px;
  ">
      <!-- Tombol kiri -->
      <a href="{{ route('pengadaan.index') }}" 
          style="background:#6c757d; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
          Kembali
      </a>

      <!-- Tombol kanan -->
      <span class="add-row-btn" onclick="addRow()">Tambah Barang</span>
  </div>



  
  <table border="1" width="100%" cellspacing="0" cellpadding="8">
    <thead>
      <tr>
        <th>Barang</th>
        <th>Jumlah</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody id="detail-body">

      <tr>
        <td>
          <select name="items[0][idbarang]" class="barang-select" required>
              <option value="">Pilih Barang</option>
              @foreach($barang as $b)
                  <option value="{{ $b->idbarang }}">{{ $b->nama }}</option>
              @endforeach
          </select>
        </td>

        <td>
          <input type="number" name="items[0][jumlah]" min="1" required>

            @error('items.0.jumlah')
                <span style="color:red; font-size:12px;">Minimal jumlah item adalah 1.</span>
            @enderror
        </td>

        <td>
          <button type="button" class="remove-row-btn" onclick="removeRow(this)">Hapus</button>
        </td>
      </tr>

    </tbody>
  </table>

  <br>

  <button type="submit" class="background:#0d6efd; padding:6px 12px; border-radius:6px; text-decoration:none;">Simpan Pengadaan</button>

</form>

<script>
let rowIndex = 1;

function addRow() {
    let tbody = document.getElementById('detail-body');

    let row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="items[${rowIndex}][idbarang]" class="barang-select" required>
                <option value="">Pilih Barang</option>
                @foreach($barang as $b)
                    <option value="{{ $b->idbarang }}">{{ $b->nama }}</option>
                @endforeach
            </select>
        </td>

        <td>
            <input type="number" name="items[${rowIndex}][jumlah]" min="1" required>
        </td>

        <td>
            <button type="button" class="remove-row-btn" onclick="removeRow(this)">Hapus</button>
        </td>
    `;

    tbody.appendChild(row);
    rowIndex++;

    attachSelectEvents();
    refreshBarangOptions();
}

function removeRow(btn) {
    let tr = btn.closest('tr');
    tr.remove();

    refreshBarangOptions();
}

function refreshBarangOptions() {
    const selects = document.querySelectorAll('.barang-select');

    // Ambil semua value yang sudah dipilih
    const selectedValues = Array.from(selects)
        .map(s => s.value)
        .filter(v => v !== '');

    selects.forEach(select => {
        const currentValue = select.value;

        Array.from(select.options).forEach(opt => {
            if (opt.value === '') {
                // opsi kosong selalu boleh
                return;
            }

            // kalau value option sudah dipakai di select lain, disable
            if (selectedValues.includes(opt.value) && opt.value !== currentValue) {
                opt.disabled = true;
                opt.style.display = 'none'; // kalau mau bener2 hilang
            } else {
                opt.disabled = false;
                opt.style.display = '';
            }
        });
    });
}

function attachSelectEvents() {
    const selects = document.querySelectorAll('.barang-select');
    selects.forEach(select => {
        select.onchange = refreshBarangOptions;
    });
}


document.addEventListener('DOMContentLoaded', function () {
    attachSelectEvents();
    refreshBarangOptions();
});
</script>

@endsection
