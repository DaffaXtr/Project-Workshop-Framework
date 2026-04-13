<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ==================== VENDOR ====================
    
    public function indexVendor()
    {
        $vendors = Vendor::all();
        return view('pages.admin.vendor.index', compact('vendors'));
    }

    public function createVendor()
    {
        return view('pages.admin.vendor.create');
    }

    public function storeVendor(Request $request)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:255'
        ]);

        Vendor::create($validated);
        return redirect()->route('admin.vendor.index')->with('success', 'Vendor berhasil ditambahkan');
    }

    public function editVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('pages.admin.vendor.edit', compact('vendor'));
    }

    public function updateVendor(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        
        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:255'
        ]);

        $vendor->update($validated);
        return redirect()->route('admin.vendor.index')->with('success', 'Vendor berhasil diperbarui');
    }

    public function destroyVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return redirect()->route('admin.vendor.index')->with('success', 'Vendor berhasil dihapus');
    }

    // ==================== MENU ====================
    
    public function indexMenu()
    {
        $menus = Menu::with('vendor')->get();
        return view('pages.admin.menu.index', compact('menus'));
    }

    public function createMenu()
    {
        $vendors = Vendor::all();
        return view('pages.admin.menu.create', compact('vendors'));
    }

    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'idvendor' => 'required|exists:vendor,idvendor',
            'path_gambar' => 'nullable|string'
        ]);

        Menu::create($validated);
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function editMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $vendors = Vendor::all();
        return view('pages.admin.menu.edit', compact('menu', 'vendors'));
    }

    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'idvendor' => 'required|exists:vendor,idvendor',
            'path_gambar' => 'nullable|string'
        ]);

        $menu->update($validated);
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui');
    }

    public function destroyMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus');
    }

    // ==================== PESANAN ====================
    
    public function indexPesanan()
    {
        $pesanans = Pesanan::with('details.menu')->orderBy('timestamp', 'desc')->get();
        return view('pages.admin.pesanan.index', compact('pesanans'));
    }

    public function updateStatusPesanan(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        $validated = $request->validate([
            'status_bayar' => 'required|in:0,1'
        ]);

        $pesanan->update($validated);
        return redirect()->route('admin.pesanan.index')->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function showPesanan($id)
    {
        $pesanan = Pesanan::with('details.menu')->findOrFail($id);
        return view('pages.admin.pesanan.show', compact('pesanan'));
    }
}
