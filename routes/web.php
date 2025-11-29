<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\Transaksi\PengadaanController;
use App\Http\Controllers\Transaksi\PenerimaanController;
use App\Http\Controllers\Transaksi\PenjualanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Master\VendorController;
use App\Http\Controllers\Master\KartuStokController;

// Halaman login
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

// Proses login
Route::post('/login', [LoginController::class, 'login'])
    ->name('login.attempt')
    ->middleware('guest');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


Route::middleware('auth')->group(function () {

Route::get('/', fn() => redirect()->route('master.index'));

Route::prefix('master')->name('master.')->group(function () {
    Route::get('/',        [MasterController::class, 'index'])->name('index');
    Route::get('/role',   [MasterController::class, 'role'])->name('role');
    Route::get('/user',   [MasterController::class, 'user'])->name('user');
    Route::get('/barang', [MasterController::class, 'barang'])->name('barang');
    Route::get('/satuan', [MasterController::class, 'satuan'])->name('satuan');
    Route::get('/margin', [MasterController::class, 'margin'])->name('margin-penjualan');
    Route::get('/vendor', [VendorController::class, 'vendor'])->name('vendor');
});

Route::prefix('master/user')->name('master.user.')->group(function () {
    Route::get('/',        [MasterController::class, 'user'])->name('index');

    Route::get('/create',  [MasterController::class, 'userCreate'])->name('create');
    Route::post('/store',  [MasterController::class, 'userStore'])->name('store');

    Route::get('/{id}/edit',   [MasterController::class, 'userEdit'])->name('edit');
    Route::post('/{id}/update',[MasterController::class, 'userUpdate'])->name('update');

    Route::post('/{id}/delete',[MasterController::class, 'userDelete'])->name('delete');
});


Route::prefix('master/barang')->name('master.barang.')->group(function () {
    Route::get('/', [MasterController::class, 'barang'])->name('index');

    Route::get('/create', [MasterController::class, 'barangCreate'])->name('create');
    Route::post('/store', [MasterController::class, 'barangStore'])->name('store');

    Route::get('/{id}/edit', [MasterController::class, 'barangEdit'])->name('edit');
    Route::post('/{id}/update', [MasterController::class, 'barangUpdate'])->name('update');

    Route::post('/{id}/delete', [MasterController::class, 'barangDelete'])->name('delete');
});

Route::prefix('master/role')->name('master.role.')->group(function () {
    Route::get('/', [MasterController::class, 'role'])->name('index');

    Route::get('/create', [MasterController::class, 'roleCreate'])->name('create');
    Route::post('/store',  [MasterController::class, 'roleStore'])->name('store');

    Route::get('/{id}/edit', [MasterController::class, 'roleEdit'])->name('edit');
    Route::post('/{id}/update', [MasterController::class, 'roleUpdate'])->name('update');

    Route::post('/{id}/delete', [MasterController::class, 'roleDelete'])->name('delete');
});

Route::prefix('master/satuan')->name('master.satuan.')->group(function () {
    Route::get('/', [MasterController::class, 'satuan'])->name('index');

    Route::get('/create', [MasterController::class, 'satuanCreate'])->name('create');
    Route::post('/store',  [MasterController::class, 'satuanStore'])->name('store');

    Route::get('/{id}/edit', [MasterController::class, 'satuanEdit'])->name('edit');
    Route::post('/{id}/update', [MasterController::class, 'satuanUpdate'])->name('update');

    Route::post('/{id}/delete', [MasterController::class, 'satuanDelete'])->name('delete');
});

Route::prefix('master/margin')->name('master.margin.')->group(function () {
    Route::get('/', [MasterController::class, 'margin'])->name('index');

    Route::get('/create',  [MasterController::class, 'marginCreate'])->name('create');
    Route::post('/store',  [MasterController::class, 'marginStore'])->name('store');

    Route::get('/{id}/edit', [MasterController::class, 'marginEdit'])->name('edit');
    Route::post('/{id}/update', [MasterController::class, 'marginUpdate'])->name('update');

    Route::post('/{id}/delete', [MasterController::class, 'marginDelete'])->name('delete');
});

Route::prefix('master/vendor')->name('master.vendor.')->group(function () {
    Route::get('/',        [VendorController::class, 'index'])->name('index');
    Route::get('/create',  [VendorController::class, 'create'])->name('create');
    Route::post('/store',  [VendorController::class, 'store'])->name('store');

    Route::get('/{id}/edit',  [VendorController::class, 'edit'])->name('edit');
    Route::post('/{id}/update', [VendorController::class, 'update'])->name('update');

    Route::post('/{id}/delete', [VendorController::class, 'delete'])->name('delete');
});

Route::prefix('master/kartu-stok')->name('master.kartu-stok.')->group(function () {

    Route::get('/',        [KartuStokController::class, 'index'])->name('index');
    Route::get('/{id}',    [KartuStokController::class, 'show'])->name('show');

});

Route::prefix('transaksi/pengadaan')->name('pengadaan.')->group(function () {
    Route::get('/',        [PengadaanController::class, 'index'])->name('index');
    Route::get('/create',  [PengadaanController::class, 'create'])->name('create');
    Route::post('/store',  [PengadaanController::class, 'store'])->name('store');
    Route::get('/{id}',      [PengadaanController::class, 'show'])->name('show');
});


Route::prefix('transaksi/penerimaan')->name('penerimaan.')->group(function () {
    Route::get('/',       [PenerimaanController::class, 'index'])->name('index');
    Route::get('/create', [PenerimaanController::class, 'create'])->name('create');
    Route::post('/store', [PenerimaanController::class, 'store'])->name('store');
    Route::get('/{id}',   [PenerimaanController::class, 'show'])->name('show');
});


Route::prefix('transaksi/penjualan')->name('penjualan.')->group(function () {
    Route::get('/',        [PenjualanController::class, 'index'])->name('index');
    Route::get('/create',  [PenjualanController::class, 'create'])->name('create');
    Route::post('/store',  [PenjualanController::class, 'store'])->name('store');
    Route::get('/{id}',    [PenjualanController::class, 'show'])->name('show');
});


});