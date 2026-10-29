# Arsitektur Aplikasi KopiZ (Laravel 13)

## 1. Pola Desain & Struktur
- **Framework**: Laravel 13.x (PHP 8.4+)
- **Arsitektur**: Model-View-Controller (MVC) murni dengan Blade Templating dan Tailwind CSS
- **Transaksional**: Database Transaction (`DB::transaction`) dengan Pessimistic Locking (`lockForUpdate()`) untuk pencegahan overselling kuota Pre-Order dan drift harga produk saat concurrent checkout.
- **Integritas Data Transaksi**:
  - `order_items` menyimpan snapshot nama produk, SKU, dan harga satuan historis saat checkout dilakukan.
  - Produk tidak dihapus secara permanen (`DELETE`), melainkan diarsipkan via `archived_at` agar laporan audit transaksi lama tetap valid.

## 2. Struktur Database Relasional
- `users`: Pengguna admin kedai kopi.
- `categories`: Pengelompokan menu dengan urutan tampilan.
- `products`: Katalog menu kopi dengan tracking inventori opsional dan status (`READY`, `PO`, `SOLD_OUT`, `INACTIVE`).
- `customers`: Profil pelanggan teragregasi otomatis dari input saat checkout dengan deduplikasi nomor telepon ternormalisasi.
- `pre_orders`: Program pemesanan pre-order batch terjadwal dengan kuota unit.
- `pre_order_products`: Pivot relasi multi-produk dalam satu batch pre-order beserta harga khusus PO.
- `orders`: Data induk pesanan reguler maupun pre-order dengan kode unik acak aman (`KPZ-YYMMDD-XXXXX`).
- `order_items`: Snapshot rincian item pesanan.
- `order_status_histories`: Catatan log audit trail setiap perubahan status pesanan.
- `settings`: Pengaturan brand, jam buka, kontak WhatsApp, Instagram, dan teks banner beranda yang dikelola dinamis oleh admin.

## 3. Kompatibilitas Deployment Vercel (Serverless PHP)
- Entrypoint `api/index.php` meneruskan request ke kernel HTTP Laravel.
- Konfigurasi `vercel.json` menggunakan runtime komunitas `vercel-php@0.7.3` dengan caching path ke `/tmp/` untuk lingkungan read-only serverless.
- Session dan Cache dikonfigurasi ke driver `database` agar persisten antar-invocations function serverless.
