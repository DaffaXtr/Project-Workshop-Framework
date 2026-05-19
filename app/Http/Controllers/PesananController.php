<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        return view('pages.scan.qrcode');
    }

    public function getDetail(Request $request)
    {
        $id = $request->idpesanan;

        $data = DB::table('detail_pesanan as dp')
            ->join('menu as m', 'dp.idmenu', '=', 'm.idmenu')
            ->join('vendor as v', 'm.idvendor', '=', 'v.idvendor')
            ->join('pesanan as p', 'dp.idpesanan', '=', 'p.idpesanan')
            ->where('dp.idpesanan', $id)
            ->select(
                'dp.idpesanan',
                'm.nama_menu',
                'v.nama_vendor',
                'dp.jumlah',
                'dp.harga',
                'dp.subtotal',
                'dp.timestamp',
                'dp.catatan',
                'p.status_bayar'
            )
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}

