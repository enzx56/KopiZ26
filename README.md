# KopiZ — Kopinya Gen-Z ☕

**Tagline:** Kopinya Gen-Z  
**Moto:** Kopi Berkualitas, Harga Pas  
**Platform:** Penjualan Kopi, Pre-Order, Operasional, dan Analitik  
**Stack Teknologi:** **Laravel 13.x + PHP 8.4/8.5 + Tailwind CSS + PostgreSQL/SQLite Ready**  
**Deployment Target:** Vercel (Serverless PHP via `vercel-php@0.7.3`) / VPS / Cloud Engine  
**Mata Uang:** Rupiah (IDR)  
**Zona Waktu:** Asia/Jakarta (WIB)

---

## ☕ Ringkasan Aplikasi

KopiZ adalah platform e-commerce dan sistem operasional kedai kopi terpadu yang memadukan:
1. **Website Pelanggan Publik**:
   - Beranda interaktif dengan palet warna resmi Coffee + Cream (`#3A2418`, `#6F4E37`, `#B9824A`, `#FFF8EF`, `#F3E5D0`).
   - Katalog menu dengan pencarian nama, filter kategori dinamis, dan ketersediaan realtime.
   - Halaman detail menu lengkap dengan informasi racikan dan selektor kuantitas.
   - Sistem Pre-Order (PO) dengan kuota unit produk, batas jadwal PO, dan jadwal pickup.
   - Keranjang belanja dan Checkout instan tanpa wajib registrasi akun.
   - Validasi harga dan stok server-side secara ketat (anti manipulasi browser).
   - Transaksi database atomik dan snapshot harga historis pada setiap item pesanan.
   - Integrasi WhatsApp deep link dengan format pemesanan resmi sesuai PRD.
   - Pelacak status pesanan publik via kode unik aman (`/pesanan/{orderCode}`).
   - Halaman statis: Tentang KopiZ, Cara Pesan, Kontak & Lokasi, Kebijakan Privasi, Syarat & Ketentuan.

2. **Dashboard Operasional Admin**:
   - Autentikasi sesi aman server-side (`/admin/login`).
   - Dashboard analitik realtime: Order hari ini, pending konfirmasi, pesanan diproses, omset selesai, produk terlaris, dan pesanan terbaru.
   - CRUD Produk & Kategori dengan status (`READY`, `PO`, `SOLD_OUT`, `INACTIVE`), soft-archival untuk menjaga integritas transaksi lama.
   - Manajemen Pre-Order: Buka batch baru, atur kuota unit, jadwal mulai/tutup/pickup, dan proteksi race condition dengan locking database.
   - Manajemen Pesanan: Update alur status (`PENDING` -> `CONFIRMED` -> `PREPARING` -> `READY_FOR_PICKUP` -> `COMPLETED`/`CANCELLED`), audit trail catatan riwayat, dan struk cetak kasir.
   - Direktori Pelanggan teragregasi otomatis dari data transaksi checkout.
   - Laporan Penjualan terfilter dengan ekspor file CSV ber-BOM UTF-8 (kompatibel penuh dengan Microsoft Excel).
   - Pengaturan Brand & Konten dinamis: Teks Hero, WhatsApp CS, Instagram, Jam Operasional, dan Alamat Pickup.

---

## 🛠️ Persyaratan Lingkungan
- **PHP**: 8.3+ (Teruji optimal pada PHP 8.4 dan 8.5)
- **Composer**: 2.x
- **Node.js & NPM**: v18+ (untuk kompilasi aset Vite & Tailwind CSS)
- **Database**: SQLite (untuk lokal/pengujian) atau PostgreSQL / Supabase / Neon (untuk production hosting)

---

## 🚀 Panduan Menjalankan Proyek Lokal

### 1. Masuk ke Direktori Proyek
```bash
cd kopiz-laravel
```

### 2. Salin Konfigurasi Environment & Install Dependensi
```bash
cp .env.example .env
composer install
npm install
npm run build
```

### 3. Generate App Key & Migrasi Database
```bash
php artisan key:generate
php artisan migrate --seed
```

### 4. Akun Admin Default
- **Email**: `admin@kopiz.id`
- **Password**: `admin123`

### 5. Jalankan Local Server
```bash
php artisan serve
```

Buka peramban:
- Website Publik: `http://localhost:8000`
- Dashboard Admin: `http://localhost:8000/admin/login`

---

## 🧪 Menjalankan Rangkaian Pengujian (Automated Tests)
Semua skenario penting (Publik, Checkout Atomik, Kuota Pre-Order, Proteksi Admin, dan Ekspor CSV) telah diuji dan lulus 100%:
```bash
php artisan test
```

---

## 🌐 Panduan Deployment ke Vercel (Siap Pakai)

File `vercel.json` dan serverless entrypoint `api/index.php` telah disiapkan di root proyek.

### Langkah-langkah Deployment:
1. Hubungkan repository Git Anda ke **Vercel** (`New Project`).
2. Buat database PostgreSQL gratis di **Neon.tech** atau **Supabase**.
3. Di Dashboard Vercel, masuk ke **Project Settings** > **Environment Variables** dan tambahkan:
   - `APP_NAME`: `KopiZ`
   - `APP_ENV`: `production`
   - `APP_DEBUG`: `false`
   - `APP_KEY`: *(Generate dari `php artisan key:generate --show`)*
   - `APP_URL`: `https://nama-project-anda.vercel.app`
   - `DB_CONNECTION`: `pgsql`
   - `DB_HOST`: `[host-neon-atau-supabase]`
   - `DB_PORT`: `5432`
   - `DB_DATABASE`: `[nama-database]`
   - `DB_USERNAME`: `[user-database]`
   - `DB_PASSWORD`: `[password-database]`
   - `SESSION_DRIVER`: `database`
   - `CACHE_STORE`: `database`
4. Jalankan migrasi dan seeder ke database PostgreSQL remote:
   ```bash
   php artisan migrate --force --seed
   ```
5. Deploy project di Vercel. Web langsung aktif dan siap digunakan secara publik!
