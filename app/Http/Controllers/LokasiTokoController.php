<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LokasiToko;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LokasiTokoController extends Controller
{
    public function index()
    {
        $tokos = LokasiToko::all();

        return view(
            'pages.lokasi-toko.index',
            compact('tokos')
        );
    }

    public function create()
    {
        return view('pages.lokasi-toko.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|max:8|unique:lokasi_toko,barcode',
            'nama_toko' => 'required|max:50',
            'latitude' => 'required',
            'longitude' => 'required',
            'accuracy' => 'required'
        ]);

        LokasiToko::create([
            'barcode' => $request->barcode,
            'nama_toko' => $request->nama_toko,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy
        ]);

        return redirect()
            ->route('lokasi-toko.index')
            ->with(
                'success',
                'Lokasi toko berhasil ditambahkan'
            );
    }

    public function qrcode($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);

        return view(
            'pages.lokasi-toko.qrcode',
            compact('toko')
        );
    }

    public function edit($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);

        return view(
            'pages.lokasi-toko.edit',
            compact('toko')
        );
    }

    public function update(Request $request, $barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);

        $request->validate([
            'nama_toko' => 'required|max:50',
            'latitude' => 'required',
            'longitude' => 'required',
            'accuracy' => 'required'
        ]);

        $toko->update([
            'nama_toko' => $request->nama_toko,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy
        ]);

        return redirect()
            ->route('lokasi-toko.index')
            ->with(
                'success',
                'Lokasi toko berhasil diperbarui'
            );
    }

    public function destroy($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);
        $toko->delete();

        return redirect()
            ->route('lokasi-toko.index')
            ->with(
                'success',
                'Lokasi toko berhasil dihapus'
            );
    }
}