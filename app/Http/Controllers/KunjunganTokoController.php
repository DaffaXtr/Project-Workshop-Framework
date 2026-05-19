<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LokasiToko;

class KunjunganTokoController extends Controller
{
    const RADIUS_TRESHOLD = 100; 
    
    public function index()
    {
        return view('pages.kunjungan-toko.index');
    }

    public function getToko($barcode)
    {
        $toko = LokasiToko::find($barcode);

        if (!$toko) {
            return response()->json([
                'success' => false,
                'message' => 'Toko tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $toko
        ]);
    }

    public function cekLokasi(Request $request)
    {
        $request->validate([
            'barcode' => 'required',
            'latitude_sales' => 'required|numeric',
            'longitude_sales' => 'required|numeric',
            'accuracy_sales' => 'required|numeric'
        ]);

        $toko = LokasiToko::find($request->barcode);

        if (!$toko) {
            return response()->json([
                'success' => false,
                'message' => 'Toko tidak ditemukan'
            ], 404);
        }

        // Hitung jarak menggunakan Haversine
        $jarak = $this->haversine(
            $toko->latitude,
            $toko->longitude,
            $request->latitude_sales,
            $request->longitude_sales
        );

        // Tambahkan accuracy untuk toleransi
        $accuracy_total = self::RADIUS_TRESHOLD + $request->accuracy_sales + $toko->accuracy;

        // Cek apakah dalam radius
        $status = $jarak <= $accuracy_total ? 'diterima' : 'ditolak';

        return response()->json([
            'success' => true,
            'toko' => $toko,
            'jarak' => round($jarak, 2),
            'accuracy_total' => round($accuracy_total, 2),
            'status' => $status,
            'message' => $status === 'diterima' 
                ? 'Kunjungan diterima' 
                : "Jarak terlalu jauh (" . round($jarak, 2) . "m > " . $accuracy_total . "m)"
        ]);
    }

    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371000; // meter

        // Konversi ke radian
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lon2 - $lon1);

        // Formula Haversine
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth_radius * $c;
    }
}
