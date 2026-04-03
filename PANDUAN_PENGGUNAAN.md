# 📚 Panduan Penggunaan Project Koleksi Buku

Dokumentasi lengkap untuk menggunakan aplikasi **Koleksi Buku** berbasis Laravel 12.

## 📋 Daftar Isi

1. [Persyaratan Sistem](#persyaratan-sistem)
2. [Instalasi](#instalasi)
3. [Konfigurasi](#konfigurasi)
4. [Fitur Utama](#fitur-utama)
5. [Panduan Pengguna](#panduan-pengguna)
6. [Troubleshooting](#troubleshooting)

---

## 🔧 Persyaratan Sistem

Pastikan server Anda memenuhi persyaratan berikut:

- **PHP**: ^8.2
- **Composer**: Latest version
- **Node.js & NPM**: Untuk build asset frontend
- **Database**: MySQL/MariaDB
- **Web Server**: Apache atau Nginx

### Package Utama

- Laravel Framework 12.0
- Laravel Socialite 5.24 (Google OAuth)
- Laravel Breeze (Authentication)
- DomPDF (PDF Generation)

---

## 🚀 Instalasi

### Step 1: Clone atau Download Project

```bash
cd d:\WS FRAMEWORK\koleksi_buku
```

### Step 2: Install Dependencies PHP

```bash
composer install
```

### Step 3: Install Dependencies JavaScript

```bash
npm install
```

### Step 4: Setup Environment

```bash
# Copy file .env
copy .env.example .env

# Generate Application Key
php artisan key:generate
```

### Step 5: Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koleksi_buku
DB_USERNAME=root
DB_PASSWORD=
```

### Step 6: Database Migration

```bash
php artisan migrate
```

### Step 7: Build Frontend Assets

```bash
npm run build
```

Untuk development dengan hot reload:

```bash
npm run dev
```

### Step 8: Jalankan Server

```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

---

## ⚙️ Konfigurasi

### 1. Google OAuth Setup

Jika ingin menggunakan fitur login Google:

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru
3. Setup OAuth 2.0 credentials
4. Dapatkan Client ID dan Client Secret
5. Update file `.env`:

```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 2. Storage Configuration

Untuk upload file, pastikan folder `storage/app/public` dapat ditulis:

```bash
php artisan storage:link
```

### 3. Database Seeding (Opsional)

Untuk populate data dummy:

```bash
php artisan db:seed
```

---

## ✨ Fitur Utama

### 1. 📖 Manajemen Buku

**Lokasi**: Menu `Buku` di Dashboard

**Fitur**:
- ✅ Tambah buku baru
- ✅ Edit data buku
- ✅ Hapus buku
- ✅ Daftar semua buku dengan kategori

**Field Data Buku**:
- Kode Buku (Unique)
- Judul
- Pengarang
- Kategori

### 2. 🏷️ Manajemen Kategori

**Lokasi**: Menu `Kategori` di Dashboard

**Fitur**:
- ✅ Tambah kategori baru
- ✅ Edit kategori
- ✅ Hapus kategori
- ✅ Lihat daftar kategori

**Field Data Kategori**:
- Nama Kategori
- Deskripsi (opsional)

### 3. 📦 Manajemen Barang

**Lokasi**: Menu `Barang` di Dashboard

**Fitur**:
- ✅ Tambah barang/inventori
- ✅ Edit data barang
- ✅ Hapus barang
- ✅ Cetak label massal
- ✅ Lihat preview cetak

**Field Data Barang**:
- Kode Barang
- Nama Barang
- Harga
- Stok
- Kategori

### 4. 💱 Penjualan (Kasir)

**Lokasi**: Menu `Kasir` di Dashboard

**Fitur**:
- ✅ Input transaksi penjualan
- ✅ Pilih barang dari katalog
- ✅ Hitung total dan kembalian
- ✅ Simpan riwayat penjualan

**Data Penjualan**:
- Nomor Transaksi (Auto)
- Tanggal
- Detail Item (barang, qty, harga)
- Total Harga
- Tipe Pembayaran

### 5. 📊 Dashboard

**Lokasi**: `/dashboard`

**Menampilkan**:
- Overview statistik
- Jumlah buku
- Jumlah kategori
- Jumlah barang
- Summary penjualan

### 6. 📄 Export PDF

**Lokasi**: Menu `PDF` di Dashboard

**Fitur**:
- ✅ Download laporan landscape
- ✅ Download laporan portrait
- ✅ Preview laporan
- ✅ Custom cetak label barang

---

## 👤 Panduan Pengguna

### Login & Autentikasi

#### Cara Login

1. Buka `http://localhost:8000`
2. Anda akan diarahkan ke halaman login
3. **2 Pilihan**:
   - **Login Manual**: Gunakan email dan password
   - **Login Google**: Klik tombol "Login dengan Google"

#### Registrasi Akun Baru

1. Di halaman login, klik `"Buat Akun Baru"` atau `"Register"`
2. Isi form:
   - Nama Lengkap
   - Email
   - Password
   - Konfirmasi Password
3. Klik `"Register"`

#### Verifikasi Email & OTP

- Setelah registrasi, cek email untuk link verifikasi
- Atau gunakan **OTP** (One-Time Password) jika tersedia
- Masukkan kode OTP yang dikirimkan ke email/SMS

### Menu Utama

Setelah login berhasil, Anda akan melihat sidebar dengan menu:

#### 📚 BUKU
- **Daftar Buku**: Lihat semua buku yang ada
- **Tambah Buku**: Buat entri buku baru
- **Edit Buku**: Update informasi buku
- **Hapus Buku**: Hapus buku dari sistem

**Contoh Input Buku**:
```
Kode: BK001
Judul: Laravel untuk Pemula
Pengarang: John Doe
Kategori: Programming
```

#### 🏷️ KATEGORI
- **Daftar Kategori**: Lihat semua kategori
- **Tambah Kategori**: Buat kategori baru
- **Edit Kategori**: Update kategori
- **Hapus Kategori**: Hapus kategori

**Contoh Kategori**:
- Programming
- Novel
- Sejarah
- Teknologi

#### 📦 BARANG
- **Daftar Barang**: Inventory semua barang
- **Tambah Barang**: Input barang baru
- **Edit Barang**: Ubah data barang
- **Cetak Label**: Print label stok untuk barang
- **Cetak Massal**: Cetak banyak label sekaligus

**Contoh Barang**:
```
Kode: BR001
Nama: Buku Laravel 5 (Hardcover)
Harga: Rp 150.000
Stok: 25
```

#### 💱 KASIR/PENJUALAN
- **Input Transaksi**: Catat penjualan baru
- **Pilih Barang**: Cari dan pilih dari katalog
- **Hitung Otomatis**: Sistem hitung total dan kembalian
- **Riwayat**: Lihat daftar transaksi

**Alur Kasir**:
1. Buka menu Kasir
2. Scan/Pilih barang
3. Masukkan jumlah
4. Sistem otomatis hitung harga
5. Input jumlah pembayaran
6. Lihat kembalian
7. Simpan & print struk

#### 📊 DASHBOARD
- **Statistik**: Ringkasan data utama
- **Grafik**: Visualisasi penjualan
- **Quick Stats**: Total buku, kategori, barang

#### 📄 PDF/LAPORAN
- **Laporan Buku**: Export daftar buku ke PDF
- **Laporan Barang**: Export inventory ke PDF
- **Label Print**: Cetak label untuk barang
- **Custom Report**: Laporan sesuai kebutuhan

### Profil Pengguna

**Lokasi**: Icon profil di sudut kanan atas

**Fitur**:
- Lihat data profil
- Edit profil
- Ubah password
- Logout

---

## 🔒 Keamanan

### Password Best Practice

- Minimal 8 karakter
- Kombinasikan huruf, angka, dan simbol
- Jangan gunakan password yang sama dengan akun lain
- Ganti password secara berkala (setiap 3-6 bulan)

### Data Privacy

- Semua data sensitif dienkripsi
- Login dengan 2FA (Google OAuth support)
- Session timeout otomatis untuk keamanan

---

## 📱 Fitur JavaScript

Project ini menggunakan JavaScript untuk:

- **Form Validation**: Validasi input real-time
- **Dynamic Forms**: Form dinamis untuk entry data
- **Interactive UI**: Interface yang responsif
- **AJAX Requests**: Request tanpa reload halaman

### Form-JS Pages

Tersedia beberapa halaman contoh JavaScript:
- `/form-js/` - Form dasar
- `/form-js/index2-7` - Berbagai variasi form
- Gunakan untuk testing dan pembelajaran

---

## 🛠️ Troubleshooting

### Error: "No Application Encryption Key"

**Solusi**:
```bash
php artisan key:generate
```

### Error: "Database Connection Refused"

**Solusi**:
1. Pastikan MySQL/MariaDB berjalan
2. Cek konfigurasi `.env` (DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD)
3. Buat database: `CREATE DATABASE koleksi_buku;`

### Error: "Class Not Found"

**Solusi**:
```bash
composer dump-autoload
```

### Error: "Storage Link Not Valid"

**Solusi**:
```bash
php artisan storage:link
```

### Asset Frontend Tidak Load

**Solusi**:
```bash
npm run build
# atau untuk development:
npm run dev
```

### Google OAuth Error

**Solusi**:
1. Verifikasi Client ID dan Secret di `.env`
2. Cek redirect URI di Google Cloud Console
3. Pastikan callback URL sesuai: `http://localhost:8000/auth/google/callback`

### Permission Denied pada File/Folder

**Solusi** (Windows):
```bash
# Pastikan folder storage dan bootstrap/cache writable
attrib -r "storage" /s /d
attrib -r "bootstrap\cache" /s /d
```

**Solusi** (Linux/Mac):
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📚 Struktur Project

```
koleksi_buku/
├── app/
│   ├── Http/Controllers/      # Controller untuk logic
│   ├── Models/                # Model data (Buku, Kategori, dll)
│   └── Providers/             # Service providers
├── routes/
│   ├── web.php               # Route aplikasi
│   └── auth.php              # Route auth
├── resources/
│   ├── views/                # Blade templates
│   ├── css/                  # Stylesheet
│   └── js/                   # JavaScript
├── database/
│   ├── migrations/           # Schema database
│   ├── seeders/              # Data seeder
│   └── factories/            # Model factories
├── config/                   # Konfigurasi aplikasi
├── storage/                  # File upload, logs, cache
└── vendor/                   # Dependencies (Composer)
```

---

## 🔗 Shortcut Route

Akses langsung ke halaman:

| Route | URL | Deskripsi |
|-------|-----|----------|
| Dashboard | `/dashboard` | Halaman utama |
| Daftar Buku | `/buku` | Manajemen buku |
| Daftar Kategori | `/kategori` | Manajemen kategori |
| Daftar Barang | `/barang` | Manajemen inventori |
| PDF | `/pdf` | Export laporan |
| Kasir | `/kasir` | Transaksi penjualan |
| Profile | `/profile` | Data pengguna |

---

## 💡 Tips & Trik

1. **Backup Database Rutin**: Backup database secara berkala
2. **Monitor Logs**: Cek file `storage/logs/laravel.log` untuk debug
3. **Gunakan Migration**: Jangan edit struktur DB langsung, gunakan migration
4. **Version Control**: Commit perubahan ke Git secara rutin
5. **Test Environment**: Gunakan `.env.testing` untuk testing
6. **Cache Clearing**: Jika ada perubahan, clear cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

---

## 📞 Support & Bantuan

Jika mengalami masalah:

1. Cek file log: `storage/logs/laravel.log`
2. Gunakan Laravel Tinker untuk debug:
   ```bash
   php artisan tinker
   ```
3. Lihat dokumentasi Laravel: [laravel.com/docs](https://laravel.com/docs)
4. Cek error page yang ditampilkan di browser

---

**Versi**: 1.0  
**Last Update**: April 2026  
**Framework**: Laravel 12  
**Author**: Project Team

---

Selamat menggunakan **Koleksi Buku**! 🚀
