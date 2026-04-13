# 📋 DOKUMENTASI SISTEM PEMESANAN MAKANAN

## ✅ Fitur Utama

1. **Katalog Produk Barang** - Tampil data barang dengan search, sort, dan filter
2. **Halaman Pemesanan** - Vendor berjenjang → Pilih menu → Keranjang
3. **Guest User Otomatis** - Format: `Guest_0000001`, `Guest_0000002`, dst
4. **Dua Metode Pembayaran** - Virtual Account (VA) atau QRIS
5. **Status Pembayaran** - Pending (0) → Lunas (1)
6. **Detail Lengkap** - Setiap pesanan menyimpan harga & catatan saat dipesan

---

## 🗂️ Struktur Database

### Table: vendor
```sql
CREATE TABLE vendor (
    idvendor INT PRIMARY KEY AUTO_INCREMENT,
    nama_vendor VARCHAR(255) NOT NULL
);
```

### Table: menu
```sql
CREATE TABLE menu (
    idmenu INT PRIMARY KEY AUTO_INCREMENT,
    nama_menu VARCHAR(255) NOT NULL,
    harga INT NOT NULL,
    path_gambar VARCHAR(255),
    idvendor INT,
    FOREIGN KEY (idvendor) REFERENCES vendor(idvendor) ON DELETE CASCADE
);
```

### Table: pesanan
```sql
CREATE TABLE pesanan (
    idpesanan INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,           -- Format: Guest_0000001
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total INT NOT NULL,                   -- Total dengan pajak
    metode_bayar VARCHAR(50),             -- 'VA' atau 'QRIS'
    status_bayar SMALLINT DEFAULT 0,      -- 0: Belum Bayar, 1: Lunas
    transaction_id VARCHAR(255),          -- ID transaksi dari gateway
    snap_token VARCHAR(255),              -- Token pembayaran
    status_message VARCHAR(255)           -- Pesan respon
);
```

### Table: detail_pesanan
```sql
CREATE TABLE detail_pesanan (
    iddetail_pesanan INT PRIMARY KEY AUTO_INCREMENT,
    idmenu INT,
    idpesanan INT,
    jumlah INT NOT NULL,
    harga INT NOT NULL,                   -- Harga saat dipesan
    subtotal INT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    catatan VARCHAR(255),                 -- Catatan khusus menu
    FOREIGN KEY (idmenu) REFERENCES menu(idmenu),
    FOREIGN KEY (idpesanan) REFERENCES pesanan(idpesanan) ON DELETE CASCADE
);
```

---

## 📁 File yang Dibuat/Dimodifikasi

### Models (✅ DIBUAT)
- `app/Models/Vendor.php` - Model Vendor
- `app/Models/Menu.php` - Model Menu
- `app/Models/Pesanan.php` - Model Pesanan (dengan auto-generate guest name)
- `app/Models/DetailPesanan.php` - Model Detail Pesanan

### Controllers (✅ DIMODIFIKASI)
- `app/Http/Controllers/CustomerController.php` - Updated dengan fitur pemesanan:
  - `index()` - Katalog produk barang
  - `order()` - Halaman pemesanan
  - `getMenuByVendor($idvendor)` - AJAX get menu by vendor
  - `storePesanan()` - Simpan pesanan
  - `payment($idpesanan)` - Halaman pembayaran
  - `confirmPayment()` - Konfirmasi pembayaran
  - `success()` - Halaman sukses

### Views (✅ DIBUAT)
- `resources/views/pages/customer/order.blade.php` - Form pemesanan
  - Pilih vendor & menu dinamis via AJAX
  - Keranjang dengan qty & catatan
  - Pilih metode pembayaran (VA / QRIS)
  
- `resources/views/pages/customer/payment.blade.php` - Halaman pembayaran
  - Instruksi pembayaran VA
  - QR Code untuk QRIS
  - Konfirmasi pembayaran

- `resources/views/pages/customer/success.blade.php` - Halaman sukses
  - Detail pesanan
  - Status pembayaran "LUNAS"
  - Langkah selanjutnya

### Routes (✅ DIMODIFIKASI)
File: `routes/web.php`

```php
// Customer routes (Tidak perlu login)
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/order', [CustomerController::class, 'order'])->name('order');
    Route::get('/menu/{idvendor}', [CustomerController::class, 'getMenuByVendor']);
    Route::post('/pesanan', [CustomerController::class, 'storePesanan'])->name('storePesanan');
    Route::get('/payment/{idpesanan}', [CustomerController::class, 'payment'])->name('payment');
    Route::post('/payment/{idpesanan}/confirm', [CustomerController::class, 'confirmPayment'])->name('confirmPayment');
    Route::get('/success/{idpesanan}', [CustomerController::class, 'success'])->name('success');
    Route::post('/payment-callback', [CustomerController::class, 'paymentCallback'])->name('paymentCallback');
});
```

---

## 🚀 Cara Menggunakan

### 1. Setup Database
Jalankan migration (jika belum ada table):
```bash
php artisan migrate
```

Atau jika sudah ada, buat table manual sesuai struktur SQL di atas.

### 2. Masukkan Data Master

**Vendor:**
```sql
INSERT INTO vendor (nama_vendor) VALUES 
('KFC Chicken'),
('Pizza Hut'),
('Burger King');
```

**Menu:**
```sql
INSERT INTO menu (nama_menu, harga, idvendor) VALUES 
('Nasi Kuning KFC', 35000, 1),
('Pizza Pepperoni', 120000, 2),
('Whopper Burger', 65000, 3);
```

### 3. Akses Fitur

**Katalog Produk (Barang):**
```
http://localhost:8000/product
```

**Pemesanan Makanan (Vendor):**
```
http://localhost:8000/customer/order
```

### 4. Alur Pemesanan

1. **Pilih Vendor** → Menu otomatis muncul via AJAX
2. **Pilih Menu** → Popup modal uncuk qty dan catatan
3. **Tambah ke Keranjang** → Tampil di ringkasan
4. **Pilih Metode Bayar** → VA atau QRIS
5. **Checkout** → Buat pesanan & guest ID auto-generate
6. **Konfirmasi Pembayaran** → Instruksi transfer/QRIS
7. **Sukses** → Status bayar berubah ke "LUNAS"

---

## 🔄 Flow Pembayaran

### Virtual Account (VA)
```
Pesanan Dibuat (Status: Pending)
    ↓
Halaman Pembayaran VA
    ↓ User transfer ke VA yang diberikan
Konfirmasi Pembayaran Manual
    ↓
Status Bayar → LUNAS (1)
```

### QRIS
```
Pesanan Dibuat (Status: Pending)
    ↓
Halaman Pembayaran QRIS
    ↓ User scan QR dengan mobile banking/e-wallet
Auto-check Status (setiap 10 detik)
    ↓
Status Bayar → LUNAS (1)
```

---

## 💡 Guest User Generation

Format: `Guest_NNNNNNN` (7 digit)

**Contoh:**
- Pesanan 1 → `Guest_0000001`
- Pesanan 2 → `Guest_0000002`
- Pesanan 100 → `Guest_0000100`

Implementasi di: `app/Models/Pesanan.php`
```php
public static function generateGuestName()
{
    $lastPesanan = self::orderBy('idpesanan', 'desc')->first();
    $lastNumber = $lastPesanan ? intval(substr($lastPesanan->nama, 6)) : 0;
    $newNumber = str_pad($lastNumber + 1, 7, '0', STR_PAD_LEFT);
    return 'Guest_' . $newNumber;
}
```

---

## ⚙️ Integrasi Payment Gateway (Opsional)

Saat ini sistem simulasi. Untuk integrasi real gateway (Midtrans, Xendit, dll):

1. **Update Controller** - `app/Http/Controllers/CustomerController.php`
   - Ubah `generateVirtualAccount()` untuk call API real
   - Ubah `generateQRIS()` untuk call API real
   - Ubah `confirmPayment()` untuk verify hasil payment

2. **Webhook Handler** - Method `paymentCallback()`
   - Set di payment gateway untuk update status_bayar → LUNAS

---

## 🎨 UI/UX Features

✅ **Responsive Design** - Mobile & Desktop  
✅ **Dynamic Menu Loading** - AJAX vendor select  
✅ **Cart Management** - Add/remove items, qty control  
✅ **Notes/Catatan** - Per-item special requests  
✅ **Tax Calculation** - Otomatis +10% pajak  
✅ **Payment Methods** - VA dengan nomor, QRIS dengan QR  
✅ **Auto-refresh** - Check payment status setiap 10 detik  

---

## 📊 Database Relationships

```
Vendor (1) ──── (Many) Menu
  ↓
  └── (Through Menu)
        ↓
      Detail Pesanan (Many) ──── (1) Pesanan
                                    ↓
                            (Has Methods & Status)
```

---

## ✨ Tips & Best Practices

1. **Generate Guest ID**: Otomatis di `storePesanan()`
2. **Harga Fixed**: Disimpan di detail_pesanan agar stabil
3. **Pajak**: Hitung & include dalam total di controller
4. **Metode Bayar**: Hanya VA atau QRIS
5. **Status**: 0=Pending, 1=Lunas

---

## 🐛 Troubleshooting

### Menu tidak muncul saat vendor dipilih
- Cek: Ada data di table menu?
- Cek: AJAX endpoint `/customer/menu/{id}` dapat diakses?

### Pesanan tidak menyimpan
- Cek: Guest name generate benar?
- Cek: Items ada di request?
- Lihat: `dd($request->all())` di controller

### Pembayaran tidak tercatat
- Cek: Method payment correct (VA/QRIS)?
- Cek: Checkbox konfirmasi dicentang?

---

## 📞 Support

Jika ada error:
1. Cek `storage/logs/laravel.log`
2. Gunakan `dd()` untuk debug
3. Test AJAX dengan browser console

---

**Status**: ✅ Ready to Use  
**Last Updated**: April 5, 2026  
**Version**: 1.0.0
