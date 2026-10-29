@extends('layouts.public')

@section('title', 'Syarat & Ketentuan — KopiZ')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="bg-white rounded-3xl border border-[#D8B894] p-8 sm:p-12 shadow-sm space-y-6 text-[#3A2418]">
        <h1 class="text-3xl font-black">Syarat & Ketentuan Pemesanan</h1>
        <p class="text-xs text-[#6F4E37]">Terakhir diperbarui: Oktober 2026</p>

        <div class="prose text-sm leading-relaxed space-y-4 text-[#6F4E37]">
            <h3 class="text-base font-bold text-[#3A2418]">1. Pemesanan Reguler & Pre-Order</h3>
            <p>
                Pelanggan dapat melakukan pemesanan langsung dari katalog menu atau program Pre-Order (PO). Khusus untuk program Pre-Order, pesanan hanya sah apabila kuota unit masih tersedia dan jadwal periode PO masih dibuka oleh pihak KopiZ.
            </p>
            <h3 class="text-base font-bold text-[#3A2418]">2. Konfirmasi & Pembayaran</h3>
            <p>
                Menekan tombol WhatsApp pada halaman pemesanan tidak secara otomatis membuktikan bahwa pesanan telah lunas atau terkonfirmasi. Status awal pesanan adalah <code>PENDING</code> hingga barista/admin KopiZ membalas pesan dan mengonfirmasi jadwal serta metode pembayaran.
            </p>
            <h3 class="text-base font-bold text-[#3A2418]">3. Pengambilan Pesanan</h3>
            <p>
                Pesanan diambil di kedai resmi KopiZ sesuai dengan alamat operasional dan jadwal perkiraan yang telah disepakati saat pemesanan. Mohon tunjukkan Kode Pesanan kepada barista kami saat melakukan pengambilan.
            </p>
        </div>
    </div>
</div>
@endsection
