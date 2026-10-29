@extends('layouts.public')

@section('title', 'Lacak Pesanan — KopiZ')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="text-center space-y-2">
        <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Pelacakan Pesanan Real-time</span>
        <h1 class="text-3xl font-black text-[#3A2418] tracking-tight">Cek & Lacak Pesanan Anda</h1>
        <p class="text-xs sm:text-sm text-[#6F4E37] max-w-md mx-auto">
            Masukkan Kode Pesanan (contoh: <code>KPZ-261029-XXXXX</code>) atau Nomor WhatsApp Anda untuk melihat status pesanan.
        </p>
    </div>

    <!-- Search Form -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#D8B894]/70 shadow-sm">
        <form action="{{ route('order.lookup') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="q" value="{{ $q }}" required placeholder="Ketik Kode Pesanan atau No. WhatsApp Anda..." class="w-full pl-12 pr-4 py-3.5 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
            </div>
            <button type="submit" class="px-8 py-3.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-black text-sm shadow-md transition-all shrink-0">
                Lacak Pesanan
            </button>
        </form>
    </div>

    <!-- Results Section -->
    @if($orders !== null)
        <div class="space-y-4">
            <h2 class="text-base font-black text-[#3A2418]">Hasil Pencarian untuk "{{ $q }}":</h2>

            @forelse($orders as $order)
                @php
                    $statusBadge = match($order->status) {
                        'PENDING' => ['bg-[#B7791F]/15', 'text-[#B7791F]', 'Menunggu Konfirmasi'],
                        'CONFIRMED' => ['bg-[#2E7D5B]/15', 'text-[#2E7D5B]', 'Dikonfirmasi Kasir'],
                        'PREPARING' => ['bg-[#B9824A]/15', 'text-[#B9824A]', 'Sedang Diracik Barista'],
                        'READY_FOR_PICKUP' => ['bg-[#2E7D5B]/20', 'text-[#2E7D5B]', 'Siap Diambil di Kedai'],
                        'COMPLETED' => ['bg-[#3A2418]', 'text-white', 'Selesai'],
                        'CANCELLED' => ['bg-[#B93838]/15', 'text-[#B93838]', 'Dibatalkan'],
                        default => ['bg-gray-100', 'text-gray-700', $order->status]
                    };
                @endphp
                <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-3">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <span class="font-mono font-black text-base text-[#3A2418]">{{ $order->order_code }}</span>
                            <span class="text-xs text-[#6F4E37] block mt-0.5">Pemesan: <strong>{{ $order->customer->name }}</strong></span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusBadge[0] }} {{ $statusBadge[1] }}">
                            {{ $statusBadge[2] }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-[#D8B894]/30 text-xs">
                        <div>
                            <span class="text-[#6F4E37]">Total:</span>
                            <span class="font-bold text-[#3A2418]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            <span class="text-[#6F4E37] ml-2">({{ $order->items->count() }} item)</span>
                        </div>

                        <a href="{{ route('order.track', $order->order_code) }}" class="px-4 py-2 rounded-xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-xs flex items-center gap-1.5 transition-all">
                            <span>Buka Halaman Pesanan Lengkap</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 rounded-3xl border border-[#D8B894]/60 text-center space-y-3">
                    <p class="text-sm font-bold text-[#3A2418]">Tidak ditemukan pesanan dengan kata kunci tersebut.</p>
                    <p class="text-xs text-[#6F4E37]">Pastikan kode pesanan atau nomor WhatsApp yang Anda masukkan sudah benar.</p>
                </div>
            @endforelse
        </div>
    @endif

    <div class="p-6 rounded-3xl bg-[#F3E5D0]/50 border border-[#D8B894]/40 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
        <div>
            <h4 class="font-bold text-[#3A2418]">Ingin simpan seluruh pesanan Anda dalam satu akun?</h4>
            <p class="text-[#6F4E37] mt-0.5">Daftarkan akun pelanggan KopiZ gratis untuk melihat riwayat kapan pun tanpa cari-cari kode.</p>
        </div>
        <a href="{{ route('customer.register') }}" class="px-5 py-2.5 rounded-xl bg-[#3A2418] text-white font-bold shrink-0 hover:bg-[#6F4E37] transition-all">
            Daftar Akun Sekarang
        </a>
    </div>
</div>
@endsection
