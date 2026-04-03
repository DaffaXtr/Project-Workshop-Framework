# ⚡ Quick Start Guide - Koleksi Buku

Panduan singkat untuk memulai dengan cepat.

---

## 🚀 Setup Awal (5 Menit)

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup .env
```bash
copy .env.example .env
php artisan key:generate
```

### 3. Konfigurasi Database
Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=koleksi_buku
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migration & Server
```bash
php artisan migrate
npm run build
php artisan serve
```

**✅ Akses di**: `http://localhost:8000`

---

## 📝 Command Shortcuts

```bash
# Server
php artisan serve                    # Jalankan server

# Database
php artisan migrate                  # Jalankan migration
php artisan migrate:fresh            # Reset & migrate ulang
php artisan db:seed                  # Seed data dummy
php artisan tinker                   # Interactive shell

# Assets
npm run dev                           # Hot reload (dev)
npm run build                         # Build untuk production

# Cache
php artisan cache:clear              # Clear cache
php artisan config:clear             # Clear config cache
php artisan view:clear               # Clear view cache
php artisan optimize:clear           # Clear semua

# Linting
./vendor/bin/pint                    # Format code
```

---

## 🔐 Login Default (Jika Ada Seeding)

```
Email: admin@example.com
Password: password
```

*Atau buat akun baru via registrasi*

---

## 📍 Halaman Utama

| Menu | URL | Fungsi |
|------|-----|--------|
| Dashboard | `/dashboard` | Overview statistik |
| Buku | `/buku` | Manajemen buku |
| Kategori | `/kategori` | Manajemen kategori |
| Barang | `/barang` | Inventory |
| Kasir | `/kasir` | Penjualan |
| PDF | `/pdf` | Export laporan |

---

## 🐛 Common Issues

| Masalah | Solusi |
|---------|--------|
| Database error | Pastikan MySQL hidup, cek `.env` |
| Asset tidak load | `npm run build` |
| Class Not Found | `composer dump-autoload` |
| Permission denied | `chmod -R 775 storage bootstrap/cache` |
| Encryption key error | `php artisan key:generate` |

---

## 📁 Folder Penting

- **app/Models/** - Data models
- **app/Http/Controllers/** - Business logic
- **resources/views/** - Template Blade
- **routes/web.php** - URL routes
- **database/migrations/** - Schema database
- **storage/logs/** - Log files

---

## 🔗 Dokumentasi Lengkap

Lihat file **PANDUAN_PENGGUNAAN.md** untuk panduan detail lengkap.

---

**Happy Coding!** 🚀
