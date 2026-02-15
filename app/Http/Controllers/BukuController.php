<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    // LIST
    public function index()
    {
        $buku = Buku::with('kategori')->get();
        return view('pages.buku.index', compact('buku'));
    }

    // FORM CREATE
    public function create()
    {
        $kategori = Kategori::all();
        return view('pages.buku.create', compact('kategori'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'pengarang' => 'required'
        ]);

        Buku::create($request->all());

        return redirect()->route('buku.index')
                         ->with('success', 'Buku berhasil ditambahkan');
    }

    // FORM EDIT
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = Kategori::all();

        return view('pages.buku.edit', compact('buku', 'kategori'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'pengarang' => 'required'
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return redirect()->route('buku.index')
                         ->with('success', 'Buku berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        Buku::destroy($id);

        return redirect()->route('buku.index')
                         ->with('success', 'Buku berhasil dihapus');
    }
}
