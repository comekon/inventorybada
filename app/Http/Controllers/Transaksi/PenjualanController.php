<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $rows = DB::select("
            SELECT *
            FROM view_transaksi_penjualan
            ORDER BY idpenjualan ASC
        ");

        return view('transaksi.penjualan.index', compact('rows'));
    }

    public function create()
    {
        // margin aktif (status = 1) – sudah view, aman
        $marginAktif = DB::selectOne("
            SELECT *
            FROM view_margin_aktif
            WHERE status = 1
            LIMIT 1
        ");

        // barang aktif + stok terakhir → pakai view_transaksi_barang_aktif_stok
        $barangList = DB::select("
            SELECT *
            FROM view_transaksi_barang_aktif_stok
            ORDER BY nama
        ");

        return view('transaksi.penjualan.create', [
            'marginAktif' => $marginAktif,
            'barangList'  => $barangList,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idmargin_penjualan' => 'required|integer',
            'items'              => 'required|array|min:1',
            'items.*.idbarang'   => 'required|integer',
            'items.*.jumlah'     => 'required|integer|min:1',
        ]);

        $items = $request->input('items', []);

        // Cek duplikat barang
        $barangIds = array_column($items, 'idbarang');
        if (count($barangIds) !== count(array_unique($barangIds))) {
            return back()
                ->withInput()
                ->with('error', 'Tidak boleh ada barang yang sama dalam satu penjualan.');
        }

        // Ambil stok terakhir untuk semua barang yang dipakai → dari view_transaksi_stok_terakhir
        $placeholders = implode(',', array_fill(0, count($barangIds), '?'));

        $stokRows = DB::select("
            SELECT 
                idbarang,
                stock
            FROM view_transaksi_stok_terakhir
            WHERE idbarang IN ($placeholders)
        ", $barangIds);

        // ubah ke map [idbarang => stock]
        $stokMap = [];
        foreach ($stokRows as $row) {
            $stokMap[$row->idbarang] = (int) $row->stock;
        }

        // Validasi stok tidak kurang
        foreach ($items as $item) {
            $idbarang = (int) $item['idbarang'];
            $jumlah   = (int) $item['jumlah'];
            $stok     = (int) ($stokMap[$idbarang] ?? 0);

            if ($jumlah > $stok) {
                return back()
                    ->withInput()
                    ->with('error', 'Melebihi jumlah stok');
            }
        }

        DB::beginTransaction();

        try {
            $iduser = auth()->id() ?? 1;

            // Header penjualan via SP
            $result = DB::select(
                'CALL insertIntoPenjualan(?, ?)',
                [$iduser, $request->idmargin_penjualan]
            );

            $idpenjualan = $result[0]->idpenjualan_baru ?? null;

            if (!$idpenjualan) {
                throw new \Exception('Gagal mengambil idpenjualan.');
            }

            // Detail penjualan via SP
            foreach ($items as $item) {
                DB::select(
                    'CALL insertIntoDetailPenjualan(?, ?, ?)',
                    [
                        $idpenjualan,
                        $item['idbarang'],
                        $item['jumlah'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('penjualan.index')
                ->with('success', 'Penjualan berhasil disimpan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // header penjualan → langsung dari view_transaksi_penjualan
        $headerRows = DB::select("
            SELECT *
            FROM view_transaksi_penjualan
            WHERE idpenjualan = ?
        ", [$id]);

        $header = $headerRows[0] ?? null;

        if (!$header) {
            abort(404);
        }

        // detail penjualan → dari view_transaksi_detail_penjualan
        $details = DB::select("
            SELECT *
            FROM view_transaksi_detail_penjualan
            WHERE idpenjualan = ?
        ", [$id]);

        return view('transaksi.penjualan.show', compact('header', 'details'));
    }
}
