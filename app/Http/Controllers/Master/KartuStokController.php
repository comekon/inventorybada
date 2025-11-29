<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class KartuStokController extends Controller
{
    public function index()
    {
        // Ambil dari VIEW (stok terakhir)
        $rows = DB::select("
            SELECT *
            FROM view_kartu_stok_barang
            ORDER BY idbarang ASC
        ");

        return view('master.kartu-stok.index', compact('rows'));
    }

    public function show($id)
    {
        // Ambil info barang dari VIEW
        $barang = DB::selectOne("
            SELECT *
            FROM view_kartu_stok_barang_detail
            WHERE idbarang = ?
        ", [$id]);

        if (!$barang) {
            abort(404, 'Barang tidak ditemukan');
        }

        // Ambil seluruh mutasi stok dari VIEW
        $mutasi = DB::select("
            SELECT *
            FROM view_kartu_stok_mutasi
            WHERE idbarang = ?
            ORDER BY created_at ASC, idkartu_stok ASC
        ", [$id]);

        return view('master.kartu-stok.show', [
            'barang' => $barang,
            'mutasi' => $mutasi,
        ]);
    }
}
