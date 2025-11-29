<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawDb;
use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('master.role.index');
    }

    // === Helper untuk summary (kartu-kartu di atas tabel) ===
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

    // === Halaman ROLE ===
    public function role()
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        $rows = $db->fetchAll(
            'SELECT idrole, nama_role FROM view_role ORDER BY idrole'
        );

        return view('master.role.index', [
            'summary' => $summary,
            'rows'    => $rows,
        ]);
    }

    // =====================
    //    USER - INDEX
    // =====================
    public function user()
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        // pakai view_user_role untuk tampilan
        $rows = $db->fetchAll('SELECT * FROM view_user_role ORDER BY iduser');

        return view('master.user.index', [
            'summary' => $summary,
            'rows'    => $rows,
        ]);
    }

    // =====================
    //    USER - CREATE
    // =====================
    public function userCreate()
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        // pakai VIEW_ROLE untuk dropdown
        $roles = $db->fetchAll('SELECT idrole, nama_role FROM view_role ORDER BY idrole');

        return view('master.user.create', [
            'summary' => $summary,
            'roles'   => $roles,
        ]);
    }

    // =====================
    //    USER - STORE
    // =====================
    public function userStore(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:45|unique:user,username',
            'password' => 'required|string|min:4',
            'idrole'   => 'required|integer|exists:role,idrole',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 4 karakter.',
            'idrole.required'   => 'Role wajib dipilih.',
        ]);

        DB::insert(
            'INSERT INTO user (username, password, idrole) VALUES (?, ?, ?)',
            [
                $data['username'],
                bcrypt($data['password']),
                $data['idrole'],
            ]
        );

        return redirect()->route('master.user.index')
            ->with('success', 'User baru berhasil ditambahkan.');
    }

    // =====================
    //    USER - EDIT
    // =====================
    public function userEdit($id)
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        $userRows = DB::select('SELECT * FROM user WHERE iduser = ?', [$id]);
        $user = $userRows[0] ?? null;

        if (!$user) {
            abort(404, 'User tidak ditemukan.');
        }

        $roles = $db->fetchAll('SELECT idrole, nama_role FROM view_role ORDER BY idrole');

        return view('master.user.edit', [
            'summary' => $summary,
            'user'    => $user,
            'roles'   => $roles,
        ]);
    }

    // =====================
    //    USER - UPDATE
    // =====================
    public function userUpdate(Request $request, $id)
    {
        $userRows = DB::select('SELECT * FROM user WHERE iduser = ?', [$id]);
        $user = $userRows[0] ?? null;

        if (!$user) {
            abort(404, 'User tidak ditemukan.');
        }

        $data = $request->validate([
            'username' => 'required|string|max:45|unique:user,username,' . $id . ',iduser',
            'password' => 'nullable|string|min:4',
            'idrole'   => 'required|integer|exists:role,idrole',
        ]);

        // kalau password diisi, update; kalau kosong, tidak diubah
        if (!empty($data['password'])) {
            DB::update(
                'UPDATE user SET username = ?, idrole = ?, password = ? WHERE iduser = ?',
                [
                    $data['username'],
                    $data['idrole'],
                    bcrypt($data['password']),
                    $id,
                ]
            );
        } else {
            DB::update(
                'UPDATE user SET username = ?, idrole = ? WHERE iduser = ?',
                [
                    $data['username'],
                    $data['idrole'],
                    $id,
                ]
            );
        }

        return redirect()->route('master.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    // =====================
    //    USER - DELETE
    // =====================
    public function userDelete($id)
    {
        try {
            DB::delete('DELETE FROM user WHERE iduser = ?', [$id]);

            return redirect()->route('master.user.index')
                ->with('success', 'User berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->route('master.user.index')
                ->with('error', 'User tidak dapat dihapus karena sudah dipakai di transaksi.');
        }
    }

    // =====================
    //    BARANG - INDEX
    // =====================
    public function barang(Request $request)
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        $statusFilter = $request->query('status', 'aktif');

        if ($statusFilter === 'aktif') {
            $view = 'view_barang_aktif';
        } else {
            $view = 'view_barang';
        }

        $rows = $db->fetchAll("SELECT * FROM {$view} ORDER BY idbarang");

        return view('master.barang.index', [
            'summary'      => $summary,
            'rows'         => $rows,
            'statusFilter' => $statusFilter,
        ]);
    }

    // =====================
    //    BARANG - CREATE
    // =====================
    public function barangCreate()
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        $satuan = $db->fetchAll("SELECT idsatuan, nama_satuan FROM view_satuan ORDER BY nama_satuan");

        return view('master.barang.create', [
            'summary' => $summary,
            'satuan'  => $satuan
        ]);
    }

    // =====================
    //    BARANG - STORE
    // =====================
    public function barangStore(Request $request)
    {
        $request->validate([
            'jenis'    => 'required',
            'nama'     => 'required',
            'idsatuan' => 'required|integer',
            'harga'    => 'required|integer',
            'status'   => 'required|integer'
        ]);

        DB::insert(
            'INSERT INTO barang (jenis, nama, idsatuan, harga, status) VALUES (?, ?, ?, ?, ?)',
            [
                $request->jenis,
                $request->nama,
                $request->idsatuan,
                $request->harga,
                $request->status,
            ]
        );

        return redirect()->route('master.barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    // =====================
    //    BARANG - EDIT
    // =====================
    public function barangEdit($id)
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        $barangRows = DB::select('SELECT * FROM barang WHERE idbarang = ?', [$id]);
        $barang = $barangRows[0] ?? null;

        if (!$barang) {
            abort(404, 'Barang tidak ditemukan.');
        }

        $satuan = $db->fetchAll('SELECT idsatuan, nama_satuan FROM view_satuan ORDER BY nama_satuan');

        return view('master.barang.edit', [
            'summary' => $summary,
            'barang'  => $barang,
            'satuan'  => $satuan
        ]);
    }

    // =====================
    //    BARANG - UPDATE
    // =====================
    public function barangUpdate(Request $request, $id)
    {
        $request->validate([
            'jenis'    => 'required',
            'nama'     => 'required',
            'idsatuan' => 'required|integer',
            'harga'    => 'required|integer',
            'status'   => 'required|integer'
        ]);

        DB::update(
            'UPDATE barang
             SET jenis = ?, nama = ?, idsatuan = ?, harga = ?, status = ?
             WHERE idbarang = ?',
            [
                $request->jenis,
                $request->nama,
                $request->idsatuan,
                $request->harga,
                $request->status,
                $id,
            ]
        );

        return redirect()->route('master.barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    // =====================
    //    BARANG - DELETE
    // =====================
    public function barangDelete($id)
    {
        DB::delete('DELETE FROM barang WHERE idbarang = ?', [$id]);

        return redirect()->route('master.barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    // =====================
    //    ROLE - CREATE
    // =====================
    public function roleCreate()
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        return view('master.role.create', [
            'summary' => $summary
        ]);
    }

    // =====================
    //    ROLE - STORE
    // =====================
    public function roleStore(Request $request)
    {
        $request->validate([
            'nama_role' => 'required'
        ]);

        DB::insert(
            'INSERT INTO role (nama_role) VALUES (?)',
            [$request->nama_role]
        );

        return redirect()->route('master.role.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    // =====================
    //    ROLE - EDIT
    // =====================
    public function roleEdit($id)
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        $roleRows = DB::select('SELECT * FROM role WHERE idrole = ?', [$id]);
        $role = $roleRows[0] ?? null;

        if (!$role) {
            abort(404, 'Role tidak ditemukan.');
        }

        return view('master.role.edit', [
            'summary' => $summary,
            'role'    => $role
        ]);
    }

    // =====================
    //    ROLE - UPDATE
    // =====================
    public function roleUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required'
        ]);

        DB::update(
            'UPDATE role SET nama_role = ? WHERE idrole = ?',
            [
                $request->nama_role,
                $id,
            ]
        );

        return redirect()->route('master.role.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    // =====================
    //    ROLE - DELETE
    // =====================
    public function roleDelete($id)
    {
        DB::delete('DELETE FROM role WHERE idrole = ?', [$id]);

        return redirect()->route('master.role.index')
            ->with('success', 'Role berhasil dihapus.');
    }

    // =====================
    //    SATUAN - INDEX
    // =====================
    public function satuan(Request $request)
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        $statusFilter = $request->query('status', 'aktif');

        if ($statusFilter === 'aktif') {
            $view = 'view_satuan_aktif';
        } else {
            $view = 'view_satuan';
        }

        $rows = $db->fetchAll("SELECT * FROM {$view} ORDER BY idsatuan");

        return view('master.satuan.index', [
            'summary'      => $summary,
            'rows'         => $rows,
            'statusFilter' => $statusFilter,
        ]);
    }

    // =====================
    //    SATUAN - CREATE
    // =====================
    public function satuanCreate()
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        return view('master.satuan.create', [
            'summary' => $summary
        ]);
    }

    // =====================
    //    SATUAN - STORE
    // =====================
    public function satuanStore(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required',
            'status'      => 'required|integer'
        ]);

        DB::insert(
            'INSERT INTO satuan (nama_satuan, status) VALUES (?, ?)',
            [
                $request->nama_satuan,
                $request->status,
            ]
        );

        return redirect()->route('master.satuan.index')
            ->with('success', 'Satuan berhasil ditambahkan.');
    }

    // =====================
    //    SATUAN - EDIT
    // =====================
    public function satuanEdit($id)
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        $satuanRows = DB::select('SELECT * FROM satuan WHERE idsatuan = ?', [$id]);
        $satuan = $satuanRows[0] ?? null;

        if (!$satuan) {
            abort(404, 'Satuan tidak ditemukan.');
        }

        return view('master.satuan.edit', [
            'summary' => $summary,
            'satuan'  => $satuan
        ]);
    }

    // =====================
    //    SATUAN - UPDATE
    // =====================
    public function satuanUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required',
            'status'      => 'required|integer'
        ]);

        DB::update(
            'UPDATE satuan SET nama_satuan = ?, status = ? WHERE idsatuan = ?',
            [
                $request->nama_satuan,
                $request->status,
                $id,
            ]
        );

        return redirect()->route('master.satuan.index')
            ->with('success', 'Satuan berhasil diperbarui.');
    }

    // =====================
    //    SATUAN - DELETE
    // =====================
    public function satuanDelete($id)
    {
        DB::delete('DELETE FROM satuan WHERE idsatuan = ?', [$id]);

        return redirect()->route('master.satuan.index')
            ->with('success', 'Satuan berhasil dihapus.');
    }


    public function margin(Request $request)
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        $statusFilter = $request->query('status', 'aktif');

        if ($statusFilter === 'aktif') {
            $view = 'view_margin_aktif';
        } else {
            $view = 'view_margin';
        }

        $rows = $db->fetchAll("SELECT * FROM {$view} ORDER BY idmargin_penjualan");

        return view('master.margin-penjualan.index', [
            'summary'      => $summary,
            'rows'         => $rows,
            'statusFilter' => $statusFilter,
        ]);
    }


    public function marginCreate()
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        return view('master.margin-penjualan.create', [
            'summary' => $summary
        ]);
    }

    // =====================
    //    MARGIN - STORE
    // =====================
    public function marginStore(Request $request)
    {
        $request->validate([
            'persen' => 'required|numeric',
            'status' => 'required|integer'
        ]);

        $idUser = auth()->id() ?? 1;

        DB::insert(
            'INSERT INTO margin_penjualan (created_at, persen, status, iduser, updated_at)
             VALUES (?, ?, ?, ?, ?)',
            [
                now(),
                $request->persen,
                $request->status,
                $idUser,
                now(),
            ]
        );

        return redirect()->route('master.margin.index')
            ->with('success', 'Margin berhasil ditambahkan.');
    }

    // =====================
    //    MARGIN - EDIT
    // =====================
    public function marginEdit($id)
    {
        $db = new RawDb();
        $summary = $this->getSummary($db);

        $marginRows = DB::select('SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?', [$id]);
        $margin = $marginRows[0] ?? null;

        if (!$margin) {
            abort(404, 'Margin tidak ditemukan.');
        }

        return view('master.margin-penjualan.edit', [
            'summary' => $summary,
            'margin'  => $margin
        ]);
    }

    // =====================
    //    MARGIN - UPDATE
    // =====================
    public function marginUpdate(Request $request, $id)
    {
        $request->validate([
            'persen' => 'required|numeric',
            'status' => 'required|integer'
        ]);

        // Jika status = 1 → nonaktifkan semua margin lain
        if ($request->status == 1) {
            DB::update(
                "UPDATE margin_penjualan SET status = 0 WHERE idmargin_penjualan != ?",
                [$id]
            );
        }

        // Update margin yang diedit
        DB::update(
            "UPDATE margin_penjualan
            SET persen = ?, status = ?, updated_at = ?
            WHERE idmargin_penjualan = ?",
            [
                $request->persen,
                $request->status,
                now(),
                $id,
            ]
        );

        return redirect()->route('master.margin.index')
                        ->with('success', 'Margin berhasil diperbarui.');
    }


    // =====================
    //    MARGIN - DELETE
    // =====================
    public function marginDelete($id)
    {
        DB::delete('DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?', [$id]);

        return redirect()->route('master.margin.index')
            ->with('success', 'Margin berhasil dihapus.');
    }
}
