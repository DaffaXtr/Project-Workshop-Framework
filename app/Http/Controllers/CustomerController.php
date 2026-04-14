<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    // LIST
    public function index()
    {
        $customers = Customer::all();
        return view('pages.customer.data-customer.index', compact('customers'));
    }

    // FORM CREATE
    public function create()
    {
        return view('pages.customer.data-customer.create');
    }

    // STORE
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

        return redirect()->route('admin.customer.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    // FORM EDIT
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('pages.customer.data-customer.edit', compact('customer'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'kodepos' => 'required|digits:5',
            'kelurahan' => 'required',
            'foto_path' => 'nullable|image|max:2048',
            'foto_blob' => 'nullable|longBlob'
        ]);

        $customer = Customer::findOrFail($id);

        $data = $request->only([
            'nama',
            'alamat',
            'provinsi',
            'kota',
            'kecamatan',
            'kodepos',
            'kelurahan'
        ]);

        // 🔥 HANDLE FOTO (BLOB)
        if ($request->hasFile('foto_path')) {
            $file = $request->file('foto_path');

            // simpan sebagai blob
            $data['foto_blob'] = file_get_contents($file->getRealPath());
        }

        $customer->update($data);

        return redirect()->route('admin.customer.index')
            ->with('success', 'Customer berhasil diperbarui');
    }

    // DELETE
    public function destroy($id)
    {
        Customer::destroy($id);

        return redirect()->route('admin.customer.index')
                         ->with('success', 'Customer berhasil dihapus');
    }
}
