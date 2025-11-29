@extends('layout.transaksi')

@section('title', 'Buat Penjualan Baru')
@section('page_title', 'Form Penjualan')

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
  table input,
  table select {
      width: 100%;
      padding: 5px;
  }
</style>

{{-- VALIDATION ERRORS --}}
@if ($errors->any())
  <div class="alert alert-danger" style="margin-bottom: 10px;">
      <ul style="margin:0; padding-left: 18px;">
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif

{{-- SESSION ERROR --}}
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('penjualan.store') }}" method="POST" id="form-penjualan">
  @csrf

  {{-- HEADER: margin aktif (otomatis) --}}
  <div style="margin-bottom:20px;">
      <label>Margin Aktif</label>
      <input type="text"
             value="{{ $marginAktif->persen }}%"
             disabled>

      {{-- simpan id margin aktif --}}
      <input type="hidden"
             name="idmargin_penjualan"
             value="{{ $marginAktif->idmargin_penjualan }}">
  </div>

  {{-- BARIS ATAS: KEMBALI & TAMBAH BARANG --}}
  <div style="
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:15px;
  ">
      <a href="{{ route('penjualan.index') }}" 
          style="background:#6c757d; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
          Kembali
      </a>

      <span class="add-row-btn" onclick="addRow()">+ Tambah Barang</span>
  </div>

  <table border="1" width="100%" cellspacing="0" cellpadding="8">
    <thead>
      <tr>
        <th>Barang</th>
        <th>Stok Tersedia</th>
        <th>Jumlah</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody id="detail-body">

      {{-- ROW DEFAULT --}}
      <tr>
        <td>
          <select name="items[0][idbarang]" class="barang-select" required>
              <option value="">Pilih Barang</option>
              @foreach($barangList as $b)
                  <option value="{{ $b->idbarang }}"
                          data-stock="{{ $b->stock ?? 0 }}">
                      {{ $b->nama }}
                  </option>
              @endforeach
          </select>
        </td>

        <td>
            <span class="stok-display">0</span>
        </td>

        <td>
          <input type="number" name="items[0][jumlah]" class="qty-input" min="1" required>
        </td>

        <td>
          <button type="button" class="remove-row-btn" onclick="removeRow(this)">Hapus</button>
        </td>
      </tr>

    </tbody>
  </table>

  <br>

  <button type="submit" class="background:#0d6efd; padding:6px 12px; border-radius:6px; text-decoration:none;">Simpan Penjualan</button>

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
                @foreach($barangList as $b)
                    <option value="{{ $b->idbarang }}"
                            data-stock="{{ $b->stock ?? 0 }}">
                        {{ $b->nama }}
                    </option>
                @endforeach
            </select>
        </td>

        <td>
            <span class="stok-display">0</span>
        </td>

        <td>
            <input type="number" name="items[${rowIndex}][jumlah]" class="qty-input" min="1" required>
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

    const selectedValues = Array.from(selects)
        .map(s => s.value)
        .filter(v => v !== '');

    selects.forEach(select => {
        const currentValue = select.value;

        Array.from(select.options).forEach(opt => {
            if (opt.value === '') return;

            if (selectedValues.includes(opt.value) && opt.value !== currentValue) {
                opt.disabled = true;
                opt.style.display = 'none';
            } else {
                opt.disabled = false;
                opt.style.display = '';
            }
        });
    });
}

function updateStockForRow(selectEl) {
    const tr        = selectEl.closest('tr');
    const stokSpan  = tr.querySelector('.stok-display');
    const qtyInput  = tr.querySelector('.qty-input');
    const opt       = selectEl.selectedOptions[0];

    let stock = 0;
    if (opt) {
        stock = parseInt(opt.getAttribute('data-stock') || '0', 10);
    }

    stokSpan.textContent = stock;

    // set batas maksimal di input jumlah
    if (stock > 0) {
        qtyInput.max = stock;
    } else {
        qtyInput.removeAttribute('max');
    }
}

function attachSelectEvents() {
    const selects = document.querySelectorAll('.barang-select');
    selects.forEach(select => {
        select.onchange = function () {
            refreshBarangOptions();
            updateStockForRow(this);
        };
    });
}

document.addEventListener('DOMContentLoaded', function () {
    attachSelectEvents();
    refreshBarangOptions();

    // pas load pertama, update stok row pertama (kalau ada value lama)
    document.querySelectorAll('.barang-select').forEach(select => {
        if (select.value) {
            updateStockForRow(select);
        }
    });

    // cek stok sebelum submit
    const form = document.getElementById('form-penjualan');
    form.addEventListener('submit', function (e) {
        const rows = document.querySelectorAll('#detail-body tr');

        for (let row of rows) {
            const select = row.querySelector('.barang-select');
            const qty    = row.querySelector('.qty-input');

            if (!select || !qty) continue;

            const opt   = select.selectedOptions[0];
            if (!opt) continue;

            const stock = parseInt(opt.getAttribute('data-stock') || '0', 10);
            const jumlah = parseInt(qty.value || '0', 10);

            if (jumlah > stock) {
                e.preventDefault();
                alert('Melebihi jumlah stok');
                qty.focus();
                return;
            }
        }
    });
});
</script>

@endsection
