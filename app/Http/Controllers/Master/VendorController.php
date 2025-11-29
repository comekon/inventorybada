<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{

    public function index(Request $request)
    {
        // baca filter dari query string, default = 'all'
        $statusFilter = $request->query('status', 'all');

        if ($statusFilter === 'aktif') {
            // hanya vendor aktif
            $rows = DB::select("
                SELECT * FROM view_vendor
                WHERE status = '1'
                ORDER BY idvendor ASC
            ");
        } else {
            // semua vendor (default)
            $rows = DB::select("
                SELECT * FROM view_vendor
                ORDER BY idvendor ASC
            ");
            $statusFilter = 'all'; // jaga-jaga kalau dapat value aneh
        }

        return view('master.vendor.index', compact('rows', 'statusFilter'));
    }

    private function getSummary(RawDb $db): array
    {
        return [
            'role'   => (int) $db->fetchScalar('SELECT COUNT(*) FROM view_role'),
            'user'   => (int) $db->fetchScalar('SELECT COUNT(*) FROM view_user_role'),
            'barang' => (int) $db->fetchScalar('SELECT COUNT(*) FROM view_barang'),
            'satuan' => (int) $db->fetchScalar('SELECT COUNT(*) FROM view_satuan'),
            'margin' => (int) $db->fetchScalar('SELECT COUNT(*) FROM margin_penjualan'),
        ];
    }


    public function create()
    {
        return view('master.vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|in:Y,T',
            'status'      => 'required|in:1,0',
        ]);

        DB::insert("
            INSERT INTO vendor (nama_vendor, badan_hukum, status)
            VALUES (?, ?, ?)
        ", [
            $request->nama_vendor,
            $request->badan_hukum,
            $request->status,
        ]);

        return redirect()->route('master.vendor.index')
            ->with('success', 'Vendor berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $row = DB::selectOne("
            SELECT * FROM vendor WHERE idvendor = ?
        ", [$id]);

        if (!$row) {
            abort(404, 'Vendor tidak ditemukan');
        }

        return view('master.vendor.edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|in:Y,T',
            'status'      => 'required|in:1,0',
        ]);

        DB::update("
            UPDATE vendor
            SET nama_vendor = ?, badan_hukum = ?, status = ?
            WHERE idvendor = ?
        ", [
            $request->nama_vendor,
            $request->badan_hukum,
            $request->status,
            $id
        ]);

        return redirect()->route('master.vendor.index')
            ->with('success', 'Vendor berhasil diupdate.');
    }

    public function delete($id)
    {
        DB::delete("DELETE FROM vendor WHERE idvendor = ?", [$id]);

        return redirect()->route('master.vendor.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }
}
