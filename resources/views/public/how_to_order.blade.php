@extends('layouts.public')

@section('title', 'Cara Pemesanan — KopiZ')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="bg-white rounded-3xl border border-[#D8B894] p-8 sm:p-12 shadow-sm space-y-8">
        <div class="text-center space-y-3">
            <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Panduan Pelanggan</span>
            <h1 class="text-3xl sm:text-4xl font-black text-[#3A2418]">Cara Pesan di KopiZ</h1>
            <p class="text-sm text-[#6F4E37] max-w-xl mx-auto">
                Proses pemesanan mudah dalam 4 langkah tanpa wajib registrasi akun.
            </p>
        </div>

        <div class="space-y-6">
            <div class="flex items-start gap-5 p-5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/40">
                <div class="w-10 h-10 rounded-xl bg-[#3A2418] text-[#FFF8EF] font-black text-base flex items-center justify-center shrink-0">1</div>
                <div>
                    <h3 class="font-bold text-[#3A2418] text-base">Pilih Menu & Jumlah</h3>
                    <p class="text-xs text-[#6F4E37] mt-1 leading-relaxed">
                        Jelajahi menu pada halaman <a href="{{ route('menu') }}" class="font-bold underline text-[#3A2418]">Katalog Menu</a> atau ikut serta dalam batch <a href="{{ route('po') }}" class="font-bold underline text-[#3A2418]">Pre-Order</a> yang sedang aktif. Tambahkan menu ke keranjang.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-5 p-5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/40">
                <div class="w-10 h-10 rounded-xl bg-[#3A2418] text-[#FFF8EF] font-black text-base flex items-center justify-center shrink-0">2</div>
                <div>
                    <h3 class="font-bold text-[#3A2418] text-base">Lakukan Checkout Sederhana</h3>
                    <p class="text-xs text-[#6F4E37] mt-1 leading-relaxed">
                        Buka keranjang dan klik Lanjut ke Checkout. Isi nama lengkap, nomor WhatsApp aktif, jam perkiraan pickup, dan catatan tambahan jika ada (misal: less ice/sugar).
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-5 p-5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/40">
                <div class="w-10 h-10 rounded-xl bg-[#3A2418] text-[#FFF8EF] font-black text-base flex items-center justify-center shrink-0">3</div>
                <div>
                    <h3 class="font-bold text-[#3A2418] text-base">Kirim Pesan WhatsApp</h3>
                    <p class="text-xs text-[#6F4E37] mt-1 leading-relaxed">
                        Setelah menekan proses, sistem KopiZ akan membuat Kode Pesanan unik dan tombol WhatsApp resmi. Klik tombol tersebut untuk membuka WhatsApp Anda dan kirimkan teks pesanan yang sudah diformat otomatis kepada barista kami.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-5 p-5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/40">
                <div class="w-10 h-10 rounded-xl bg-[#3A2418] text-[#FFF8EF] font-black text-base flex items-center justify-center shrink-0">4</div>
                <div>
                    <h3 class="font-bold text-[#3A2418] text-base">Konfirmasi & Ambil di Kedai</h3>
                    <p class="text-xs text-[#6F4E37] mt-1 leading-relaxed">
                        Admin kami akan membalas pesan WhatsApp untuk mengonfirmasi ketersediaan dan metode pembayaran. Anda dapat mengambil pesanan di kedai KopiZ sesuai jadwal.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center pt-4">
            <a href="{{ route('menu') }}" class="inline-block px-8 py-3.5 rounded-2xl bg-[#3A2418] text-white font-bold text-sm shadow-md hover:bg-[#6F4E37] transition-all">
                Mulai Pesan Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
