@extends('layouts.public')

@section('title', 'Pesanan Saya — KopiZ')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Customer Header Profile -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#D8B894]/70 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#3A2418] text-[#FFF8EF] flex items-center justify-center font-black text-2xl shadow-sm shrink-0">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div>
                <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Akun Pelanggan KopiZ</span>
                <h1 class="text-2xl font-black text-[#3A2418] tracking-tight">{{ $customer->name }}</h1>
                <div class="flex flex-wrap items-center gap-3 text-xs text-[#6F4E37] mt-1">
                    <span>📱 {{ $customer->phone }}</span>
                    @if($customer->email)
                        <span>✉️ {{ $customer->email }}</span>
                    @endif
                    @if($customer->instagram_handle)
                        <span>📸 {{ $customer->instagram_handle }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('menu') }}" class="px-5 py-2.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-xs flex items-center gap-2 shadow-sm transition-all text-center">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Pesan Kopi Baru</span>
            </a>
            <form action="{{ route('customer.logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2.5 rounded-2xl bg-[#F3E5D0] hover:bg-[#D8B894] text-[#3A2418] text-xs font-bold transition-all" title="Keluar">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Order History List -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-black text-[#3A2418] flex items-center gap-2">
                <i data-lucide="clock" class="w-5 h-5 text-[#B9824A]"></i>
                <span>Riwayat Pesanan Anda</span>
            </h2>
            <span class="text-xs font-bold text-[#6F4E37]">Total: {{ $orders->total() }} Pesanan</span>
        </div>

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
            <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4 hover:border-[#B9824A] transition-all">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#D8B894]/30 pb-3">
                    <div class="flex items-center gap-3">
                        <span class="font-mono font-black text-sm text-[#3A2418]">
                            {{ $order->order_code }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusBadge[0] }} {{ $statusBadge[1] }}">
                            {{ $statusBadge[2] }}
                        </span>
                        <span class="text-[11px] text-[#6F4E37]">
                            {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : '-' }} WIB
                        </span>
                    </div>

                    <a href="{{ route('order.track', $order->order_code) }}" class="px-4 py-1.5 rounded-xl bg-[#FFF8EF] hover:bg-[#F3E5D0] text-[#3A2418] text-xs font-bold border border-[#D8B894] flex items-center gap-1.5 transition-all">
                        <span>Pantau & Rincian</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="sm:col-span-2 space-y-1">
                        <span class="text-[#6F4E37] block font-semibold">Menu yang Dipesan:</span>
                        <div class="space-y-0.5">
                            @foreach($order->items as $it)
                                <div class="text-[#3A2418]">
                                    • <strong>{{ $it->product_name_snapshot }}</strong> ({{ $it->quantity }}x) — Rp{{ number_format($it->subtotal, 0, ',', '.') }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-1 sm:text-right">
                        <span class="text-[#6F4E37] block">Total Pembayaran:</span>
                        <span class="text-base font-black text-[#2E7D5B] block">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        <span class="text-[11px] text-[#6F4E37] block">Pickup: {{ $order->pickup_at ?: '-' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-3xl border border-[#D8B894]/60 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-[#FFF8EF] text-[#B9824A] flex items-center justify-center mx-auto text-2xl">
                    ☕
                </div>
                <h3 class="text-base font-bold text-[#3A2418]">Belum Ada Pesanan Tercatat</h3>
                <p class="text-xs text-[#6F4E37] max-w-sm mx-auto">Anda belum melakukan pesanan kopi di KopiZ. Yuk coba racikan kopi spesial kami sekarang!</p>
                <a href="{{ route('menu') }}" class="inline-block px-6 py-3 rounded-2xl bg-[#3A2418] text-white font-bold text-xs hover:bg-[#6F4E37] transition-all">
                    Lihat Menu Kopi
                </a>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
