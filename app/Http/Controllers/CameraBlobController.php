<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CameraBlobController extends Controller
{

    public function index()
    {
        return view('pages.customer.add-customer1.index');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'kodepos' => 'required|digits:5',
            'kelurahan' => 'required',
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_blob' => 'nullable|longBlob'
        ]);

        $data = $request->only([
            'nama', 'alamat', 'provinsi', 'kota',
            'kecamatan', 'kodepos', 'kelurahan', 'foto_path', 'foto_blob'
        ]);

        // Handle foto blob from camera modal
        if ($request->hasFile('foto_path')) {
            $file = $request->file('foto_path');
            $data['foto_blob'] = file_get_contents($file->getRealPath());
        }
        Customer::create($data);

        return redirect()->route('camera.blob.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }
}
