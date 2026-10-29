# Status Tugas & Roadmap KopiZ (Laravel 13)

- [x] **Fase 0: Audit & Inisialisasi Proyek**
  - [x] Verifikasi PHP 8.4+ dan Composer 2.9+.
  - [x] Inisialisasi proyek Laravel 13 (`laravel/framework v13.39.0`).
  - [x] Konfigurasi timezone `Asia/Jakarta` dan locale Indonesia.
  - [x] Siapkan `README.md`, `ARCHITECTURE.md`, dan `TASKS.md`.

- [x] **Fase 1: Basis Data & Model Relasional**
  - [x] Migrasi schema terindeks (`users`, `categories`, `products`, `customers`, `pre_orders`, `pre_order_products`, `orders`, `order_items`, `order_status_histories`, `inventory_movements`, `settings`).
  - [x] Model Eloquent dengan relasi, casting, dan query scope.
  - [x] Database Seeder lengkap dengan 3 menu awal (*Butterscotch*, *Kopi Susu Gula Aren*, *Salted Caramel*), batch PO aktif, pengaturan brand, dan akun admin.

- [x] **Fase 2: UI Publik & Design System**
  - [x] Palet warna resmi Coffee + Cream (`#3A2418`, `#6F4E37`, `#B9824A`, `#FFF8EF`, `#F3E5D0`).
  - [x] Halaman Beranda (`/`): Hero banner, produk populer, pre-order aktif, cara pesan.
  - [x] Halaman Katalog Menu (`/menu`): Pencarian, filter kategori, sorting harga/nama, pagination.
  - [x] Halaman Detail Menu (`/menu/{slug}`): Info racikan, pengatur kuantitas, tombol pesan & checkout instan.
  - [x] Halaman Pre-Order Publik (`/po` & `/po/{slug}`): Kuota tersisa, batas waktu, jadwal pickup.
  - [x] Halaman Statis: Tentang, Cara Pesan, Kontak & Lokasi, Kebijakan Privasi, Syarat & Ketentuan.

- [x] **Fase 3: Keranjang & Transaksi Checkout**
  - [x] Keranjang belanja sisi klien terintegrasi localStorage & badge counter navbar.
  - [x] Checkout tanpa akun pelanggan.
  - [x] Validasi server-side ketat terhadap manipulasi harga dan status produk.
  - [x] Transaksi database atomik dan pembuatan kode pesanan acak aman (`KPZ-YYMMDD-XXXXX`).
  - [x] Snapshot harga historis pada `order_items`.
  - [x] Generator deep link WhatsApp resmi sesuai format PRD Bagian 9.
  - [x] Halaman pelacak status pesanan publik (`/pesanan/{orderCode}`).

- [x] **Fase 4: Sistem Pre-Order (PO) & Anti Race-Condition**
  - [x] Transaksi checkout dengan pessimistic locking (`lockForUpdate()`) untuk mencegah overselling kuota PO.
  - [x] Pengembalian kuota otomatis jika pesanan PO dibatalkan oleh admin.

- [x] **Fase 5: Autentikasi & Dashboard Operasional Admin**
  - [x] Login & Logout Admin aman dengan hashing password (`/admin/login`).
  - [x] Proteksi middleware otentikasi di seluruh rute `/admin/*`.
  - [x] Dashboard analitik berbasis data nyata database (order hari ini, pending, dalam proses, omset selesai, menu terlaris, pesanan terbaru).
  - [x] CRUD Produk & Kategori dengan toggle status instan dan soft-archival.
  - [x] CRUD Pre-Order (jadwal, kuota unit, harga khusus PO).
  - [x] Manajemen Pesanan: Update alur status (`PENDING` s/d `COMPLETED`/`CANCELLED`), catatan internal, audit log riwayat, dan struk cetak kasir.
  - [x] Direktori Pelanggan teragregasi otomatis dari pesanan.
  - [x] Pengaturan Brand & Konten dinamis (Hero, kontak WhatsApp, Instagram, jam operasional, alamat pickup).

- [x] **Fase 6: Laporan Bisnis & Ekspor CSV**
  - [x] Filter laporan tanggal, status order, dan jenis order.
  - [x] Metrik laporan: Total order, penjualan selesai (COMPLETED), nilai order non-batal, dan pesanan batal dilaporkan terpisah.
  - [x] Ekspor file CSV dengan UTF-8 BOM untuk kompatibilitas penuh Microsoft Excel.

- [x] **Fase 7: Verifikasi, Pengujian & Konfigurasi Siap Hosting**
  - [x] Seluruh PHPUnit Feature Tests lulus 100% (`php artisan test`).
  - [x] Konfigurasi `vercel.json` dan entrypoint `api/index.php` untuk hosting serverless Vercel.
  - [x] Dokumentasi lengkap di `README.md`.
