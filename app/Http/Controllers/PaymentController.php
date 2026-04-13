<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        try {
            Log::info('Midtrans Callback Received', $request->all());

            // Verifikasi signature dari Midtrans
            $serverKey = env('MIDTRANS_SERVER_KEY');
            $orderId = $request->order_id;
            $statusCode = $request->status_code;
            $grossAmount = $request->gross_amount;
            $signatureKey = $request->signature_key;

            // Generate signature untuk verifikasi
            $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signatureKey !== $signature) {
                Log::warning('Invalid Midtrans Signature: ' . $signatureKey . ' !== ' . $signature);
                return response()->json(['status' => 'invalid_signature'], 403);
            }

            $pesanan = Pesanan::find($orderId);
            
            if (!$pesanan) {
                Log::warning('Pesanan not found for order_id: ' . $orderId);
                return response()->json(['status' => 'pesanan_not_found'], 404);
            }

            $transactionStatus = $request->transaction_status;

            Log::info('Processing transaction status: ' . $transactionStatus . ' for order: ' . $orderId);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // Endpoint untuk update status dari frontend (alternative method)
    public function updateStatus(Request $request, $orderId)
    {
        try {
            $pesanan = Pesanan::find($orderId);
            
            if (!$pesanan) {
                return response()->json(['status' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
            }

            $transactionStatus = $request->transaction_status ?? 'settlement';

            Log::info('Processing transaction status: ' . $transactionStatus . ' for order: ' . $orderId);

            // Handle status transaksi
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                $pesanan->update([
                    'status_bayar' => 1,
                    'transaction_id' => $request->transaction_id ?? null,
                    'status_message' => 'Pembayaran berhasil'
                ]);
                Log::info('Payment marked as SUCCESS for order: ' . $orderId);
            } 

            return response()->json([
                'status' => true,
                'message' => 'Status pesanan berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            Log::error('Update Status Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}