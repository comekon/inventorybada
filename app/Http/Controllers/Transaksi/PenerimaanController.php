<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index()
    {
        // langsung dari VIEW
        $rows = DB::select("
            SELECT *
            FROM view_transaksi_penerimaan
            ORDER BY idpenerimaan ASC
        ");

        return view('transaksi.penerimaan.index', compact('rows'));
    }

    public function create(Request $request)
    {
        // daftar pengadaan yang masih bisa diterima: PROSES (P) atau SEBAGIAN (B)
        // pakai view_transaksi_pengadaan yang sudah kita buat di controller pengadaan
        $pengadaanList = DB::select("
            SELECT 
                idpengadaan,
                nama_vendor,
                `timestamp`,
                status
            FROM view_transaksi_pengadaan
            WHERE status IN ('P', 'B')
            ORDER BY idpengadaan DESC
        ");

        $selectedId        = $request->query('idpengadaan');
        $selectedPengadaan = null;
        $detailPengadaan   = collect();

        if ($selectedId) {
            // header pengadaan terpilih (pakai VIEW)
            $selectedRows = DB::select("
                SELECT *
                FROM view_transaksi_pengadaan
                WHERE idpengadaan = ?
            ", [$selectedId]);

            $selectedPengadaan = $selectedRows[0] ?? null;

            if ($selectedPengadaan) {
                // detail pengadaan + total_terima + sisa → lewat view_transaksi_pengadaan_sisa
                $detailPengadaan = collect(DB::select("
                    SELECT *
                    FROM view_transaksi_pengadaan_sisa
                    WHERE idpengadaan = ?
                      AND sisa > 0
                ", [$selectedId]));
            }
        }

        return view('transaksi.penerimaan.create', [
            'pengadaanList'     => $pengadaanList,
            'selectedId'        => $selectedId,
            'selectedPengadaan' => $selectedPengadaan,
            'detailPengadaan'   => $detailPengadaan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpengadaan'           => 'required|integer',
            'items'                 => 'required|array|min:0',
            'items.*.idbarang'      => 'required|integer',
            'items.*.jumlah_terima' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $iduser = auth()->id() ?? 1;

            // 1. header penerimaan via SP insertIntoPenerimaan(p_idpengadaan, p_iduser, p_status)
            $result = DB::select(
                'CALL insertIntoPenerimaan(?, ?, ?)',
                [
                    $request->idpengadaan,
                    $iduser,
                    'S',    // status penerimaan (sesuai definisi kamu)
                ]
            );

            $idpenerimaan = $result[0]->idpenerimaan_baru ?? null;

            if (!$idpenerimaan) {
                throw new \Exception('Gagal mengambil idpenerimaan.');
            }

            // 2. detail penerimaan via SP insertIntoDetailPenerimaan
            foreach ($request->items as $item) {

                // Ambil harga dari VIEW detail pengadaan dulu (lebih konsisten)
                $rowHarga = DB::selectOne(
                    "SELECT harga_satuan 
                     FROM view_transaksi_detail_pengadaan
                     WHERE idpengadaan = ? AND idbarang = ?",
                    [$request->idpengadaan, $item['idbarang']]
                );

                $harga = $rowHarga->harga_satuan ?? null;

                // Kalau entah kenapa view detail pengadaan tidak nemu, fallback ke VIEW_BARANG
                if ($harga === null) {
                    $rowBarang = DB::selectOne(
                        "SELECT harga 
                         FROM view_barang 
                         WHERE idbarang = ?",
                        [$item['idbarang']]
                    );

                    if ($rowBarang === null) {
                        throw new \Exception(
                            'Harga tidak ditemukan untuk barang ID '.$item['idbarang']
                        );
                    }

                    $harga = $rowBarang->harga;
                }

                DB::select(
                    'CALL insertIntoDetailPenerimaan(?, ?, ?, ?)',
                    [
                        $idpenerimaan,
                        $item['idbarang'],
                        $item['jumlah_terima'],
                        $harga,   // diambil dari VIEW, bukan dari form
                    ]
                );
            }

            // 3. setelah semua detail masuk, hitung & update status pengadaan (P / B / S)
            $this->updatePengadaanStatus($request->idpengadaan);

            DB::commit();

            return redirect()->route('penerimaan.index')
                ->with('success', 'Penerimaan berhasil disimpan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        // header penerimaan: cukup dari VIEW
        $headerRows = DB::select("
            SELECT *
            FROM view_transaksi_penerimaan
            WHERE idpenerimaan = ?
        ", [$id]);

        $header = $headerRows[0] ?? null;

        if (!$header) {
            abort(404, 'Data penerimaan tidak ditemukan.');
        }

        // detail penerimaan: VIEW detail + barang
        $details = DB::select("
            SELECT *
            FROM view_transaksi_detail_penerimaan
            WHERE idpenerimaan = ?
        ", [$id]);

        return view('transaksi.penerimaan.show', compact('header', 'details'));
    }

    /**
     * Hitung status pengadaan (P = Proses, B = Sebagian, S = Selesai)
     * berdasarkan:
     *  - total pesan per barang (view_transaksi_pengadaan_pesan)
     *  - total terima per barang (view_transaksi_pengadaan_terima)
     */
    private function updatePengadaanStatus(int $idpengadaan): void
    {
        // total yang dipesan per barang
        $pesan = DB::select("
            SELECT 
                idbarang,
                total_pesan
            FROM view_transaksi_pengadaan_pesan
            WHERE idpengadaan = ?
        ", [$idpengadaan]);

        if (empty($pesan)) {
            // kalau tidak ada detail pengadaan, anggap Proses saja
            DB::update(
                "UPDATE pengadaan SET status = ? WHERE idpengadaan = ?",
                ['P', $idpengadaan]
            );
            return;
        }

        // total yang diterima per barang
        $terimaRows = DB::select("
            SELECT 
                idbarang,
                total_terima
            FROM view_transaksi_pengadaan_terima
            WHERE idpengadaan = ?
        ", [$idpengadaan]);

        // buat map idbarang -> total_terima
        $terimaMap = [];
        foreach ($terimaRows as $row) {
            $terimaMap[$row->idbarang] = (int) $row->total_terima;
        }

        $allZero = true;  // semua belum diterima?
        $allFull = true;  // semua sudah terpenuhi?

        foreach ($pesan as $p) {
            $totalPesan = (int) $p->total_pesan;
            $ter        = isset($terimaMap[$p->idbarang])
                            ? (int) $terimaMap[$p->idbarang]
                            : 0;

            if ($ter > 0) {
                $allZero = false;
            }
            if ($ter < $totalPesan) {
                $allFull = false;
            }
        }

        $status = 'P'; // default Proses

        if (!$allZero && !$allFull) {
            $status = 'B'; // Sebagian
        }

        if ($allFull) {
            $status = 'S'; // Selesai
        }

        DB::update(
            "UPDATE pengadaan SET status = ? WHERE idpengadaan = ?",
            [$status, $idpengadaan]
        );
    }
}
