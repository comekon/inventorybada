<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');

        // base SQL + parameter → pakai VIEW
        $sql    = "
            SELECT 
                *
            FROM view_transaksi_pengadaan
        ";
        $params = [];

        // filter status: P = Proses, B = Sebagian, S = Selesai
        if ($statusFilter === 'proses') {
            $sql      .= " WHERE status = ?";
            $params[] = 'P';
        } elseif ($statusFilter === 'sebagian') {
            $sql      .= " WHERE status = ?";
            $params[] = 'B';
        } elseif ($statusFilter === 'selesai') {
            $sql      .= " WHERE status = ?";
            $params[] = 'S';
        }

        $sql  .= " ORDER BY idpengadaan ASC";

        $rows = DB::select($sql, $params);

        return view('transaksi.pengadaan.index', [
            'rows'         => $rows,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function create()
    {
        // sudah aman, ini pakai VIEW master
        $barang = DB::select("SELECT * FROM view_barang_aktif ORDER BY nama");
        $vendor = DB::select("SELECT * FROM view_vendor_aktif ORDER BY nama_vendor");

        return view('transaksi.pengadaan.create', compact('barang', 'vendor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idvendor'            => 'required|integer',
            'status'              => 'required',            // hidden, default 'P'
            'items'               => 'required|array|min:1',
            'items.*.idbarang'    => 'required|integer',
            'items.*.jumlah'      => 'required|integer|min:1',
        ]);

        $items     = $request->input('items', []);
        $barangIds = array_column($items, 'idbarang');

        // cek duplikasi barang
        if (count($barangIds) !== count(array_unique($barangIds))) {
            return back()
                ->withInput()
                ->with('error', 'Tidak boleh ada barang yang sama dalam satu pengadaan.');
        }

        DB::beginTransaction();

        try {
            $iduser = auth()->id() ?? 1;

            // 1) SP insertIntoPengadaan(p_iduser, p_status, p_idvendor)
            $result = DB::select(
                'CALL insertIntoPengadaan(?, ?, ?)',
                [
                    $iduser,
                    $request->status,   // 'P'
                    $request->idvendor,
                ]
            );

            $idpengadaan = $result[0]->idpengadaan_baru ?? null;

            if (!$idpengadaan) {
                throw new \Exception('Gagal mengambil idpengadaan dari prosedur.');
            }

            // 2) SP insertDetailPengadaan  
            foreach ($items as $item) {
                $barang = DB::selectOne(
                    "SELECT harga FROM view_barang WHERE idbarang = ?",
                    [$item['idbarang']]
                );

                if (!$barang) {
                    throw new \Exception("Barang ID {$item['idbarang']} tidak ditemukan.");
                }

                DB::select(
                    'CALL insertDetailPengadaan(?, ?, ?, ?)',
                    [
                        $idpengadaan,
                        $item['idbarang'],
                        $barang->harga,        // harga_satuan dari VIEW
                        $item['jumlah'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('pengadaan.index')
                ->with('success', 'Pengadaan berhasil disimpan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        // header pengadaan: pakai VIEW_TRANSAKSI
        $headerRows = DB::select(
            "
            SELECT 
                *
            FROM view_transaksi_pengadaan
            WHERE idpengadaan = ?
            ",
            [$id]
        );

        $header = $headerRows[0] ?? null;

        if (!$header) {
            abort(404, 'Data pengadaan tidak ditemukan');
        }

        // detail pengadaan: pakai VIEW_TRANSAKSI_DETAIL_PENGADAAN
        $details = DB::select(
            "
            SELECT 
                *
            FROM view_transaksi_detail_pengadaan
            WHERE idpengadaan = ?
            ",
            [$id]
        );

        return view('transaksi.pengadaan.show', [
            'header'  => $header,
            'details' => $details,
        ]);
    }
}
