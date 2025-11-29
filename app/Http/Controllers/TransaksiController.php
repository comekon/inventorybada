<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function penerimaan(Request $request)
    {
        return view('transaksi.penerimaan.index');
    }
}