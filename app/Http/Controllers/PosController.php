<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PosController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('pages.pos.index', compact('vendors'));
    }

    public function getMenu($vendorId)
    {
        return Menu::where('idvendor', $vendorId)->get();
    }

    public function checkout(Request $request)
    {
        try {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false;

            // Validasi request
            if (!$request->items || count($request->items) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong'
                ], 400);
            }

            $guest = generateGuestName();

            $total = collect($request->items)->sum(function ($item) {
                return $item['harga'] * $item['qty'];
            });

            $pesanan = Pesanan::create([
                'nama' => $guest,
                'total' => $total,
                'metode_bayar' => 'MIDTRANS',
                'status_bayar' => 0
            ]);

            foreach ($request->items as $item) {
                DetailPesanan::create([
                    'idmenu' => $item['idmenu'],
                    'idpesanan' => $pesanan->idpesanan,
                    'jumlah' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['harga'] * $item['qty'],
                ]);
            }

            // MIDTRANS
            $params = [
                'transaction_details' => [
                    'order_id' => $pesanan->idpesanan,
                    'gross_amount' => $total,
                ]
                // 'enabled_payments' => [
                //     'credit_card',
                //     'bank_transfer',
                //     'qris'
                // ]
            ];

            $snapToken = Snap::getSnapToken($params);

            if ($snapToken) {
                $pesanan->update([
                    'snap_token' => $snapToken,
                    'transaction_id' => $request->transaction_id ?? null,
                    'status_message' => 'Menunggu pembayaran'
                ]);
            } else {
                throw new \Exception('Gagal mendapatkan snap token dari Midtrans');
            }

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'pesanan_id' => $pesanan->idpesanan
            ]);

        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error checkout: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateQrCode($pesananId)
    {
        try {
            $qr = QrCode::format('svg')
                ->size(100)
                ->margin(1)
                ->generate('PES-' . $pesananId);

            return response($qr)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        } catch (\Exception $e) {
            Log::error('QR Code generation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error generating QR Code: ' . $e->getMessage()
            ], 500);
        }
    }
}