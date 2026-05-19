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
                ->generate($pesananId);

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

    // Halaman history pesanan customer
    public function history()
    {
        return view('pages.pos.history');
    }

    // API untuk get detail pesanan berdasarkan ID
    public function getReceiptDetail($pesananId)
    {
        try {
            $pesanan = Pesanan::with('details.menu')->find($pesananId);
            
            if (!$pesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            $details = $pesanan->details->map(function ($detail) {
                return [
                    'idmenu' => $detail->idmenu,
                    'nama_menu' => $detail->menu->nama_menu ?? 'Unknown Menu',
                    'harga' => $detail->harga,
                    'qty' => $detail->jumlah,
                    'subtotal' => $detail->subtotal
                ];
            });

            return response()->json([
                'success' => true,
                'pesanan_id' => $pesanan->idpesanan,
                'nama' => $pesanan->nama,
                'timestamp' => $pesanan->timestamp,
                'total' => $pesanan->total,
                'status_bayar' => $pesanan->status_bayar,
                'transaction_id' => $pesanan->transaction_id,
                'items' => $details
            ]);
        } catch (\Exception $e) {
            Log::error('Get receipt detail error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching receipt: ' . $e->getMessage()
            ], 500);
        }
    }

    // API untuk get history pesanan dari array IDs
    public function getOrdersHistory(Request $request)
    {
        try {
            $pesananIds = $request->pesanan_ids ?? [];
            
            if (empty($pesananIds)) {
                return response()->json([
                    'success' => true,
                    'orders' => []
                ]);
            }

            $orders = Pesanan::whereIn('idpesanan', $pesananIds)
                ->orderBy('timestamp', 'DESC')
                ->get()
                ->map(function ($pesanan) {
                    return [
                        'idpesanan' => $pesanan->idpesanan,
                        'nama' => $pesanan->nama,
                        'timestamp' => $pesanan->timestamp,
                        'total' => $pesanan->total,
                        'status_bayar' => $pesanan->status_bayar,
                        'transaction_id' => $pesanan->transaction_id,
                        'status_message' => $pesanan->status_message
                    ];
                });

            return response()->json([
                'success' => true,
                'orders' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('Get history error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching history: ' . $e->getMessage()
            ], 500);
        }
    }
}