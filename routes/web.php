<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\FormJsController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CameraBlobController;
use App\Http\Controllers\CameraPathController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\OtpController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login-purple');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Customer routes (Tidak perlu login)
Route::get('/customer', [PosController::class, 'index'])->name('customer.index');
Route::get('/menu/{vendor}', [PosController::class, 'getMenu'])->name('customer.getMenu');
Route::post('/checkout', [PosController::class, 'checkout'])->name('customer.checkout');
Route::get('/qrcode/{pesananId}', [PosController::class, 'generateQrCode'])->name('qrcode.generate');
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/update-status/{orderId}', [PaymentController::class, 'updateStatus'])->name('payment.updateStatus');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kategori
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('index');
        Route::get('/create', [KategoriController::class, 'create'])->name('create');
        Route::post('/store', [KategoriController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [KategoriController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [KategoriController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [KategoriController::class, 'destroy'])->name('destroy');
    });

    // Buku
    Route::prefix('buku')->name('buku.')->group(function () {
        Route::get('/', [BukuController::class, 'index'])->name('index');
        Route::get('/create', [BukuController::class, 'create'])->name('create');
        Route::post('/store', [BukuController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BukuController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [BukuController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BukuController::class, 'destroy'])->name('destroy');
    });

    // PDF
    Route::get('/pdf', [PdfController::class, 'index'])->name('pdf.index');
    Route::get('/pdf/download-landscape', [PdfController::class, 'landscape'])->name('pdf.landscape');
    Route::get('/pdf/download-portrait', [PdfController::class, 'portrait'])->name('pdf.portrait');
    Route::get('/pdf/view', [PdfController::class, 'view'])->name('pdf.view');

    // Barang
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::get('/create', [BarangController::class, 'create'])->name('create');
        Route::post('/store', [BarangController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BarangController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [BarangController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BarangController::class, 'destroy'])->name('destroy');
        // Route::get('/cetak-label/{id}', [BarangController::class, 'cetakLabel'])->name('cetakLabel');
        Route::post('/cetak-massal', [BarangController::class, 'cetakMassal'])->name('cetakMassal');
        Route::get('/view-cetak', [BarangController::class, 'viewCetak'])->name('viewCetak');
    });

    Route::prefix('form-js')->name('form-js.')->group(function () {
        Route::get('/', [FormJsController::class, 'index'])->name('index');
        Route::get('/index2', [FormJsController::class, 'index2'])->name('index2');
        Route::get('/index3', [FormJsController::class, 'index3'])->name('index3');
        Route::get('/index4', [FormJsController::class, 'index4'])->name('index4');
        Route::get('/index5', [FormJsController::class, 'index5'])->name('index5');
        Route::get('/index6', [FormJsController::class, 'index6'])->name('index6');
        Route::get('/index7', [FormJsController::class, 'index7'])->name('index7');
        Route::get('/create', [FormJsController::class, 'create'])->name('create');
        Route::post('/store', [FormJsController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [FormJsController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [FormJsController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [FormJsController::class, 'destroy'])->name('destroy');
    });

    // Kasir
    Route::prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/', [KasirController::class, 'index'])->name('index');
        Route::get('/ajax', [KasirController::class, 'ajaxVersion'])->name('ajax');
        Route::get('/axios', [KasirController::class, 'axiosVersion'])->name('axios');
        Route::get('/get-barang', [KasirController::class, 'getBarang'])->name('get-barang');
        Route::post('/save-penjualan', [KasirController::class, 'savePenjualan'])->name('save-penjualan');
    });

    // Customer Data Management
    Route::prefix('admin/customer')->name('admin.customer.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/store', [CustomerController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::get('/customer/foto/{id}', function($id) {
            $c = \App\Models\Customer::findOrFail($id);

            return response($c->foto_blob)
                ->header('Content-Type', 'image/png');
        });
    });

    Route::prefix('camera/path')->name('camera.path.')->group(function () {
        Route::get('/', [CameraPathController::class, 'index'])->name('index');
        Route::post('/store', [CameraPathController::class, 'store'])->name('store');
    });

    Route::prefix('camera/blob')->name('camera.blob.')->group(function () {
        Route::get('/', [CameraBlobController::class, 'index'])->name('index');
        Route::post('/store', [CameraBlobController::class, 'store'])->name('store');
    });

    // ==================== ADMIN ROUTES ====================
    
    // Vendor Management
    Route::prefix('admin/vendor')->name('admin.vendor.')->group(function () {
        Route::get('/', [AdminController::class, 'indexVendor'])->name('index');
        Route::get('/create', [AdminController::class, 'createVendor'])->name('create');
        Route::post('/', [AdminController::class, 'storeVendor'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editVendor'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateVendor'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyVendor'])->name('destroy');
    });

    // Menu Management
    Route::prefix('admin/menu')->name('admin.menu.')->group(function () {
        Route::get('/', [AdminController::class, 'indexMenu'])->name('index');
        Route::get('/create', [AdminController::class, 'createMenu'])->name('create');
        Route::post('/', [AdminController::class, 'storeMenu'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editMenu'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateMenu'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyMenu'])->name('destroy');
    });

    // Pesanan Management
    Route::prefix('admin/pesanan')->name('admin.pesanan.')->group(function () {
        Route::get('/', [AdminController::class, 'indexPesanan'])->name('index');
        Route::get('/{id}', [AdminController::class, 'showPesanan'])->name('show');
        Route::put('/{id}', [AdminController::class, 'updateStatusPesanan'])->name('update');
    });

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Google Authentication
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('/verify-otp', [OtpController::class, 'form'])->name('otp.form');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');

require __DIR__.'/auth.php';
