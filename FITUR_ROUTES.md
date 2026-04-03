# 🗺️ Fitur & Routes Map - Koleksi Buku

Dokumentasi lengkap semua fitur dan endpoint aplikasi.

---

## 📋 Daftar Fitur Utama

### ✅ Authentication & Autentikasi
- [x] Login manual (email + password)
- [x] Registrasi akun baru
- [x] Google OAuth Login
- [x] Email verification
- [x] OTP verification
- [x] Password reset
- [x] Profile management

### ✅ Manajemen Buku
- [x] Create (Tambah buku)
- [x] Read (Lihat daftar buku)
- [x] Update (Edit buku)
- [x] Delete (Hapus buku)
- [x] Filter by kategori
- [x] Search buku

### ✅ Manajemen Kategori
- [x] Create (Tambah kategori)
- [x] Read (Lihat daftar kategori)
- [x] Update (Edit kategori)
- [x] Delete (Hapus kategori)

### ✅ Manajemen Barang/Inventori
- [x] Create (Tambah barang)
- [x] Read (Lihat daftar barang)
- [x] Update (Edit barang)
- [x] Delete (Hapus barang)
- [x] Print label
- [x] Print label massal
- [x] View print preview

### ✅ Penjualan & Kasir
- [x] Input transaksi penjualan
- [x] Pilih barang dari katalog
- [x] Hitung total otomatis
- [x] Hitung kembalian
- [x] Simpan riwayat penjualan
- [x] Lihat detail penjualan

### ✅ Laporan & Export
- [x] Export PDF landscape
- [x] Export PDF portrait
- [x] Preview laporan
- [x] Custom format cetak
- [x] Label barang

### ✅ Dashboard & Analytics
- [x] Overview statistik
- [x] Grafik penjualan
- [x] Summary data
- [x] Quick stats

---

## 🔗 Routes API

### 🔓 Public Routes (Tanpa Login)

#### Authentication
```
GET  /                           Login page
POST /register                   Register user
POST /login                      Login user
GET  /auth/google/redirect       Google OAuth redirect
GET  /auth/google/callback       Google OAuth callback
GET  /forgot-password            Reset password page
POST /forgot-password            Send reset link
GET  /reset-password/{token}     Reset password form
POST /reset-password             Update password
```

---

### 🔒 Protected Routes (Memerlukan Login)

#### Dashboard
```
GET  /dashboard         Dashboard utama [DashboardController@index]
```

#### Kategori Management
```
GET    /kategori              Daftar kategori [KategoriController@index]
GET    /kategori/create       Form tambah kategori [KategoriController@create]
POST   /kategori/store        Simpan kategori baru [KategoriController@store]
GET    /kategori/edit/{id}    Form edit kategori [KategoriController@edit]
PUT    /kategori/update/{id}  Update kategori [KategoriController@update]
DELETE /kategori/delete/{id}  Hapus kategori [KategoriController@destroy]
```

#### Buku Management
```
GET    /buku              Daftar buku [BukuController@index]
GET    /buku/create       Form tambah buku [BukuController@create]
POST   /buku/store        Simpan buku baru [BukuController@store]
GET    /buku/edit/{id}    Form edit buku [BukuController@edit]
PUT    /buku/update/{id}  Update buku [BukuController@update]
DELETE /buku/delete/{id}  Hapus buku [BukuController@destroy]
```

#### Barang Management
```
GET    /barang                Daftar barang [BarangController@index]
GET    /barang/create         Form tambah barang [BarangController@create]
POST   /barang/store          Simpan barang baru [BarangController@store]
GET    /barang/edit/{id}      Form edit barang [BarangController@edit]
PUT    /barang/update/{id}    Update barang [BarangController@update]
DELETE /barang/delete/{id}    Hapus barang [BarangController@destroy]
POST   /barang/cetak-massal   Cetak label massal [BarangController@cetakMassal]
GET    /barang/view-cetak     Preview cetak [BarangController@viewCetak]
```

#### PDF & Laporan
```
GET /pdf                       Daftar laporan PDF [PdfController@index]
GET /pdf/download-landscape    Download landscape PDF [PdfController@landscape]
GET /pdf/download-portrait     Download portrait PDF [PdfController@portrait]
GET /pdf/view                  Preview PDF [PdfController@view]
```

#### Kasir/Penjualan
```
GET    /kasir          Dashboard kasir [KasirController@index]
POST   /kasir/store    Simpan transaksi [KasirController@store]
GET    /kasir/history  Riwayat penjualan [KasirController@history]
GET    /kasir/receipt  Print struk [KasirController@receipt]
```

#### Profile
```
GET    /profile           Lihat profil [ProfileController@show]
GET    /profile/edit      Form edit profil [ProfileController@edit]
PUT    /profile/update    Update profil [ProfileController@update]
PUT    /password          Update password [ProfileController@updatePassword]
```

#### Form-JS (Testing/Learning)
```
GET /form-js/           Form dasar [FormJsController@index]
GET /form-js/index2     Variasi form 2 [FormJsController@index2]
GET /form-js/index3     Variasi form 3 [FormJsController@index3]
GET /form-js/index4     Variasi form 4 [FormJsController@index4]
GET /form-js/index5     Variasi form 5 [FormJsController@index5]
GET /form-js/index6     Variasi form 6 [FormJsController@index6]
GET /form-js/index7     Variasi form 7 [FormJsController@index7]
GET /form-js/create     Form create [FormJsController@create]
POST /form-js/store     Simpan [FormJsController@store]
GET /form-js/edit/{id}  Form edit [FormJsController@edit]
PUT /form-js/update/{id} Update [FormJsController@update]
DELETE /form-js/delete/{id} Hapus [FormJsController@destroy]
```

---

## 📊 Database Structure

### Tabel Utama

#### users
```
- id (PK)
- name
- email (UNIQUE)
- email_verified_at
- password
- id_google
- otp
- remember_token
- created_at
- updated_at
```

#### kategori
```
- idkategori (PK)
- nama_kategori
- deskripsi (nullable)
- created_at
- updated_at
```

#### buku
```
- idbuku (PK)
- kode (UNIQUE)
- judul
- pengarang
- idkategori (FK -> kategori)
- created_at
- updated_at
```

#### barang
```
- idbarang (PK)
- kode (UNIQUE)
- nama_barang
- harga
- stok
- idkategori (FK -> kategori)
- created_at
- updated_at
```

#### penjualan
```
- idpenjualan (PK)
- no_transaksi
- tgl_penjualan
- id_user (FK -> users)
- total_harga
- tipe_pembayaran
- created_at
- updated_at
```

#### penjualan_detail
```
- iddetail (PK)
- idpenjualan (FK -> penjualan)
- idbarang (FK -> barang)
- qty
- harga_satuan
- subtotal
- created_at
- updated_at
```

---

## 🏃 Controllers & Methods

### DashboardController
```php
- index()           // Tampilkan dashboard
```

### KategoriController
```php
- index()           // Daftar kategori
- create()          // Form tambah
- store()           // Simpan kategori
- edit($id)         // Form edit
- update($id)       // Update kategori
- destroy($id)      // Hapus kategori
```

### BukuController
```php
- index()           // Daftar buku
- create()          // Form tambah
- store()           // Simpan buku
- edit($id)         // Form edit
- update($id)       // Update buku
- destroy($id)      // Hapus buku
```

### BarangController
```php
- index()           // Daftar barang
- create()          // Form tambah
- store()           // Simpan barang
- edit($id)         // Form edit
- update($id)       // Update barang
- destroy($id)      // Hapus barang
- cetakMassal()     // Cetak label massal
- viewCetak()       // Preview cetak
```

### PdfController
```php
- index()           // Daftar laporan
- landscape()       // Download landscape
- portrait()        // Download portrait
- view()            // Preview
```

### KasirController
```php
- index()           // Dashboard kasir
- store()           // Simpan transaksi
- history()         // Riwayat penjualan
- receipt()         // Print struk
```

### ProfileController
```php
- show()            // Tampilkan profil
- edit()            // Form edit profil
- update()          // Update profil
- updatePassword()  // Update password
```

### GoogleController (Auth)
```php
- redirect()        // Google OAuth redirect
- callback()        // Google OAuth callback
```

### OtpController (Auth)
```php
- sendOtp()         // Kirim OTP
- verifyOtp()       // Verifikasi OTP
```

---

## 🎨 Models & Relationships

### Buku Model
```php
- Relasi: belongsTo(Kategori)
- Primary Key: idbuku
- Attributes: kode, judul, pengarang, idkategori
```

### Barang Model
```php
- Relasi: belongsTo(Kategori)
- Primary Key: idbarang
- Attributes: kode, nama_barang, harga, stok, idkategori
```

### Kategori Model
```php
- Relasi: hasMany(Buku), hasMany(Barang)
- Primary Key: idkategori
- Attributes: nama_kategori, deskripsi
```

### Penjualan Model
```php
- Relasi: belongsTo(User), hasMany(PenjualanDetail)
- Primary Key: idpenjualan
- Attributes: no_transaksi, tgl_penjualan, id_user, total_harga, tipe_pembayaran
```

### PenjualanDetail Model
```php
- Relasi: belongsTo(Penjualan), belongsTo(Barang)
- Primary Key: iddetail
- Attributes: idpenjualan, idbarang, qty, harga_satuan, subtotal
```

### User Model
```php
- Relasi: hasMany(Penjualan)
- Attributes: name, email, password, id_google, otp
```

---

## 🔐 Middleware & Auth

- **auth**: Memerlukan login
- **verified**: Email harus terverifikasi
- **auth.google**: Google OAuth
- **auth.otp**: OTP verification

Contoh penggunaan:
```php
Route::middleware(['auth', 'verified'])->group(function () {
    // Routes yang protected
});
```

---

## 📦 Dependencies

### Production
- `laravel/framework: ^12.0`
- `laravel/socialite: ^5.24` (Google OAuth)
- `laravel/tinker: ^2.10.1`
- `barryvdh/laravel-dompdf: ^3.1` (PDF)

### Development
- `laravel/breeze: ^2.3` (Auth scaffolding)
- `phpunit/phpunit: ^11.5.3` (Testing)
- `laravel/pint: ^1.24` (Code formatting)
- `laravel/pail: ^1.2.2` (Log viewer)

---

## 🧪 Testing

Jalankan unit tests:
```bash
php artisan test

# Dengan coverage
php artisan test --coverage
```

Test files berlokasi di:
- `tests/Feature/` - Feature tests
- `tests/Unit/` - Unit tests

---

## 📱 Frontend Assets

### CSS
- `resources/css/app.css` - Main CSS
- Menggunakan Tailwind CSS (via config)

### JavaScript
- `resources/js/app.js` - Main JS
- `resources/js/bootstrap.js` - Bootstrap config
- Assets di-bundle dengan Vite

### Build Commands
```bash
npm run dev      # Development dengan hot reload
npm run build    # Production build
```

---

## 🚨 Error Handling

### Common HTTP Status Codes
- 200 OK
- 201 Created
- 400 Bad Request
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found
- 422 Unprocessable Entity
- 500 Internal Server Error

### Logging
Semua errors tercatat di:
```
storage/logs/laravel.log
```

---

## 🔄 Data Flow

Contoh alur tambah buku:

```
User -> Form (/buku/create)
  ↓
[BukuController@store]
  ↓
[Validation]
  ↓
[Buku Model->create()]
  ↓
[Database]
  ↓
[Redirect /buku dengan message]
```

---

## ⚙️ Configuration Files

- `config/app.php` - App config
- `config/auth.php` - Auth config
- `config/database.php` - Database config
- `config/filesystems.php` - Storage config
- `.env` - Environment variables
- `vite.config.js` - Vite config
- `tailwind.config.js` - Tailwind config
- `phpunit.xml` - PHPUnit config

---

**Last Updated**: April 2026  
**Framework**: Laravel 12  
**Version**: 1.0

---

Lihat **PANDUAN_PENGGUNAAN.md** untuk panduan pengguna lengkap.
