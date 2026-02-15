<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    // READ
    public function index()
    {
        $kategori = Kategori::all();
        return view('pages.kategori.index', compact('kategori'));
    }

    // CREATE FORM
    public function create()
    {
        return view('pages.kategori.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->route('kategori.index')
                         ->with('success', 'Data berhasil ditambahkan');
    }

    // EDIT FORM
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('pages.kategori.edit', compact('kategori'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required'
        ]);

        $kategori = Kategori::findOrFail($id);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->route('kategori.index')
                         ->with('success', 'Data berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        Kategori::destroy($id);

        return redirect()->route('kategori.index')
                         ->with('success', 'Data berhasil dihapus');
    }
}
