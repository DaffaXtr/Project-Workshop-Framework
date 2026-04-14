<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CameraPathController extends Controller
{

    public function index()
    {
        return view('pages.customer.add-customer2.index');
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
            'foto_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only([
            'nama', 'alamat', 'provinsi', 'kota',
            'kecamatan', 'kodepos', 'kelurahan'
        ]);

        if ($request->hasFile('foto_path')) {
            $path = $request->file('foto_path')->store('customers', 'public');
            $data['foto_path'] = $path;
        }

        Customer::create($data);

        return redirect()->route('camera.path.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }
}
