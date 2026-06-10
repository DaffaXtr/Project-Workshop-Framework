<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    // =========================
    // HALAMAN
    // =========================

    public function guest()
    {
        return view('pages.antrian.guest.index');
    }

    public function admin()
    {
        return view('pages.antrian.admin.index');
    }

    public function papan()
    {
        return view('pages.antrian.papan.index');
    }

    // =========================
    // TAMBAH ANTRIAN
    // =========================
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'poli' => 'required|string'
    ]);

    // Ambil semua antrian
    $queues = Cache::get('queues', []);

    // Nomor terakhir
    $lastNumber = Cache::get('last_number', 0);

    // Nomor baru
    $newNumber = $lastNumber + 1;

    // Tambahkan data baru
    $queues[] = [
        'nomor' => $newNumber,
        'nama' => $request->nama,
        'poli' => $request->poli,
        'status' => 'menunggu',
        'created_at' => now()->toDateTimeString()
    ];

    // Simpan ke cache
    Cache::put('queues', $queues);

    // Simpan nomor terakhir
    Cache::put('last_number', $newNumber);

    // Jika request AJAX, return JSON
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'nomor' => $newNumber,
            'nama' => $request->nama,
            'poli' => $request->poli
        ]);
    }

    // Redirect dengan session untuk traditional form submission
    return back()->with([
        'success' => true,
        'nomor' => $newNumber,
        'nama' => $request->nama,
        'poli' => $request->poli
    ]);
}

    // =========================
    // PANGGIL ANTRIAN BERIKUTNYA
    // =========================
    public function panggil()
    {
        $queues = Cache::get('queues', []);

        // Reset yang sebelumnya dipanggil
        foreach ($queues as $i => $queue) {

            if ($queue['status'] === 'dipanggil') {
                $queues[$i]['status'] = 'selesai';
            }
        }

        // Cari antrian menunggu pertama
        foreach ($queues as $i => $queue) {

            if ($queue['status'] === 'menunggu') {

                $queues[$i]['status'] = 'dipanggil';

                Cache::put('queues', $queues);

                return response()->json([
                    'success' => true,
                    'message' => 'Antrian dipanggil',
                    'data' => $queues[$i]
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada antrian'
        ]);
    }

    // =========================
    // MASUKKAN KE TERLAMBAT
    // =========================
    public function terlambat(Request $request)
    {
        $nomor = $request->nomor;

        $queues = Cache::get('queues', []);

        foreach ($queues as $i => $queue) {

            if ($queue['nomor'] == $nomor) {

                $queues[$i]['status'] = 'terlambat';

                break;
            }
        }

        Cache::put('queues', $queues);

        return response()->json([
            'success' => true
        ]);
    }

    // =========================
    // PANGGIL ULANG TERLAMBAT
    // =========================
    public function panggilTerlambat(Request $request)
    {
        $nomor = $request->nomor;

        $queues = Cache::get('queues', []);

        // Reset yang sedang dipanggil
        foreach ($queues as $i => $queue) {

            if ($queue['status'] === 'dipanggil') {
                $queues[$i]['status'] = 'selesai';
            }
        }

        // Cari nomor terlambat
        foreach ($queues as $i => $queue) {

            if (
                $queue['nomor'] == $nomor &&
                $queue['status'] === 'terlambat'
            ) {

                $queues[$i]['status'] = 'dipanggil';

                Cache::put('queues', $queues);

                return response()->json([
                    'success' => true,
                    'data' => $queues[$i]
                ]);
            }
        }

        return response()->json([
            'success' => false
        ]);
    }

    // =========================
    // RESET ANTRIAN
    // =========================
    public function reset()
    {
        Cache::forget('queues');
        Cache::forget('last_number');

        return response()->json([
            'success' => true,
            'message' => 'Antrian berhasil direset'
        ]);
    }

    // =========================
    // SSE STREAM
    // =========================
    public function stream()
    {
        // Hindari timeout PHP
        set_time_limit(0);

        // Bersihkan output buffering
        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        return response()->stream(function () {

            // SSE hidup hanya 1 detik
            // lalu browser reconnect otomatis
            $start = time();

            while (time() - $start < 1) {

                $queues = Cache::get('queues', []);

                $dipanggil = null;

                foreach ($queues as $queue) {
                    if ($queue['status'] === 'dipanggil') {
                        $dipanggil = $queue;
                        break;  
                    }
                }

                $data = [
                    'queues' => $queues,
                    'dipanggil' => $dipanggil,
                    'total' => count($queues),
                    'timestamp' => now()->format('H:i:s')
                ];

                // retry reconnect browser
                echo "retry: 1000\n";

                // nama event
                echo "event: queue-update\n";

                // data
                echo "data: " . json_encode($data) . "\n\n";

                // flush output
                if (ob_get_level() > 0) {
                    ob_flush();
                }

                flush();

                // jika browser disconnect
                if (connection_aborted()) {
                    break;
                }

                // delay 1 detik
                sleep(1);
            }

        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}