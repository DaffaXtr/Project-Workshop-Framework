<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        return view('pages.form-js.index6');
    }

    // ================= AJAX VERSION =================
    public function ajaxVersion()
    {
        return view('pages.form-js.index6');
    }

    // ================= AXIOS VERSION =================
    public function axiosVersion()
    {
        return view('pages.form-js.index7');
    }
    // ================= GET BARANG BERDASARKAN KODE =================
    public function getBarang(Request $request)
    {
        $kode = $request->input('kode');

        if (empty($kode)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode barang tidak boleh kosong'
            ], 400);
        }

        $barang = Barang::where('id_barang', $kode)->first();

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id_barang' => $barang->id_barang,
                'nama' => $barang->nama,
                'harga' => $barang->harga
            ]
        ]);
    }

    // ================= SIMPAN PENJUALAN KE DATABASE =================
    public function savePenjualan(Request $request)
    {
        try {
            DB::beginTransaction();

            $items = $request->input('items', []);

            if (empty($items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada item untuk disimpan'
                ], 400);
            }

            // Hitung total
            $total = array_reduce($items, function ($carry, $item) {
                return $carry + ($item['subtotal'] ?? 0);
            }, 0);

            // Buat penjualan
            $penjualan = Penjualan::create([
                'total' => $total,
                'timestamp' => now()
            ]);

            // Simpan detail penjualan
            foreach ($items as $item) {
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penjualan berhasil disimpan!',
                'data' => [
                    'id_penjualan' => $penjualan->id_penjualan,
                    'total' => $total
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
