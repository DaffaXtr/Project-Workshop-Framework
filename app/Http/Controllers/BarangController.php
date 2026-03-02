<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('pages.barang.index', compact('barang'));
    }

    public function cetakMassal(Request $request)
    {
        $request->validate([
            'barang_ids' => 'required|array',
            'start_x' => 'required|integer|min:1|max:5',
            'start_y' => 'required|integer|min:1|max:8',
        ]);

        $barang = Barang::whereIn('id_barang', $request->barang_ids)->get();

        $startIndex = (($request->start_y - 1) * 5) + ($request->start_x - 1);

        $pdf = Pdf::loadView('pages.barang.label-massal', [
            'barang' => $barang,
            'startIndex' => $startIndex
        ]);

        return $pdf->setPaper('a4', 'portrait')
                ->stream('label-massal.pdf');
    }
    
    public function create()
    {
        return view('pages.barang.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('pages.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric'
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil dihapus');
    }

    public function viewCetak()
    {
        $barang = Barang::all();
        return view('pages.barang.label-massal', compact('barang'));
    }
}