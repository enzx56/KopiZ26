@extends('layouts.public')

@section('title', 'Status Pesanan ' . $order->order_code . ' — KopiZ')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl border border-[#D8B894] p-6 sm:p-10 shadow-sm text-center space-y-6">
        @php
            $isPending = $order->status === 'PENDING';
            $isConfirmed = in_array($order->status, ['CONFIRMED', 'PREPARING', 'READY_FOR_PICKUP', 'COMPLETED']);
            $isPreparing = in_array($order->status, ['PREPARING', 'READY_FOR_PICKUP', 'COMPLETED']);
            $isReady = in_array($order->status, ['READY_FOR_PICKUP', 'COMPLETED']);
            $isCompleted = $order->status === 'COMPLETED';
            $isCancelled = $order->status === 'CANCELLED';

            $statusBadge = match($order->status) {
                'PENDING' => ['bg-[#B7791F]/15', 'text-[#B7791F]', 'Menunggu Konfirmasi Admin / Kasir'],
                'CONFIRMED' => ['bg-[#2E7D5B]/15', 'text-[#2E7D5B]', 'Pesanan Telah Dikonfirmasi ✓'],
                'PREPARING' => ['bg-[#B9824A]/15', 'text-[#B9824A]', 'Sedang Diracik oleh Barista ☕'],
                'READY_FOR_PICKUP' => ['bg-[#2E7D5B]/20', 'text-[#2E7D5B]', 'Siap Diambil di Kedai! 🎉'],
                'COMPLETED' => ['bg-[#3A2418]', 'text-white', 'Selesai — Terima Kasih!'],
                'CANCELLED' => ['bg-[#B93838]/15', 'text-[#B93838]', 'Pesanan Dibatalkan'],
                default => ['bg-gray-100', 'text-gray-700', $order->status]
            };
        @endphp

        <!-- Icon Header -->
        <div class="w-16 h-16 rounded-full {{ $statusBadge[0] }} {{ $statusBadge[1] }} flex items-center justify-center mx-auto text-2xl">
            @if($isCancelled)
                <i data-lucide="x-circle" class="w-9 h-9"></i>
            @elseif($isReady)
                <i data-lucide="bell-ring" class="w-9 h-9"></i>
            @elseif($isPreparing)
                <i data-lucide="coffee" class="w-9 h-9"></i>
            @else
                <i data-lucide="check-circle" class="w-9 h-9"></i>
            @endif
        </div>

        <div>
            <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Pelacakan Status Pesanan</span>
            <h1 class="text-3xl font-black text-[#3A2418] tracking-tight mt-1">Kode: {{ $order->order_code }}</h1>
            <p class="text-sm text-[#6F4E37] mt-2">
                Halo <strong>{{ $order->customer->name }}</strong>, berikut perkembangan status kopi pesanan Anda.
            </p>
            <div class="mt-3">
                <span class="inline-block px-4 py-1.5 rounded-full text-xs font-black {{ $statusBadge[0] }} {{ $statusBadge[1] }}">
                    {{ $statusBadge[2] }}
                </span>
            </div>
        </div>

        <!-- Progress Tracker Bar -->
        @if(!$isCancelled)
            <div class="p-6 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/60">
                <div class="grid grid-cols-5 gap-2 text-center text-[10px] sm:text-xs">
                    <!-- Step 1: Dipesan -->
                    <div class="space-y-1">
                        <div class="w-8 h-8 rounded-full bg-[#2E7D5B] text-white flex items-center justify-center mx-auto font-bold shadow-sm">
                            ✓
                        </div>
                        <span class="font-bold text-[#3A2418] block">Dipesan</span>
                    </div>

                    <!-- Step 2: Dikonfirmasi -->
                    <div class="space-y-1">
                        <div class="w-8 h-8 rounded-full {{ $isConfirmed ? 'bg-[#2E7D5B] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center mx-auto font-bold shadow-sm">
                            {{ $isConfirmed ? '✓' : '2' }}
                        </div>
                        <span class="font-bold {{ $isConfirmed ? 'text-[#3A2418]' : 'text-gray-400' }} block">Konfirmasi</span>
                    </div>

                    <!-- Step 3: Diracik -->
                    <div class="space-y-1">
                        <div class="w-8 h-8 rounded-full {{ $isPreparing ? 'bg-[#2E7D5B] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center mx-auto font-bold shadow-sm">
                            {{ $isPreparing ? '✓' : '3' }}
                        </div>
                        <span class="font-bold {{ $isPreparing ? 'text-[#3A2418]' : 'text-gray-400' }} block">Diracik</span>
                    </div>

                    <!-- Step 4: Siap Diambil -->
                    <div class="space-y-1">
                        <div class="w-8 h-8 rounded-full {{ $isReady ? 'bg-[#2E7D5B] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center mx-auto font-bold shadow-sm">
                            {{ $isReady ? '✓' : '4' }}
                        </div>
                        <span class="font-bold {{ $isReady ? 'text-[#3A2418]' : 'text-gray-400' }} block">Siap Diambil</span>
                    </div>

                    <!-- Step 5: Selesai -->
                    <div class="space-y-1">
                        <div class="w-8 h-8 rounded-full {{ $isCompleted ? 'bg-[#3A2418] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center mx-auto font-bold shadow-sm">
                            {{ $isCompleted ? '✓' : '5' }}
                        </div>
                        <span class="font-bold {{ $isCompleted ? 'text-[#3A2418]' : 'text-gray-400' }} block">Selesai</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- WhatsApp Action Card (Terutama saat PENDING) -->
        @if($isPending)
            <div class="p-6 rounded-2xl bg-[#E8F5E9] border border-[#2E7D5B]/30 space-y-4 text-left">
                <div class="flex items-start gap-3">
                    <i data-lucide="message-circle" class="w-6 h-6 text-[#2E7D5B] shrink-0 mt-0.5"></i>
                    <div>
                        <h3 class="text-base font-bold text-[#2E7D5B]">Konfirmasi Pesanan ke WhatsApp KopiZ</h3>
                        <p class="text-xs text-[#2E7D5B]/90 mt-0.5 leading-relaxed">
                            Pesanan Anda sedang menunggu verifikasi kasir. Silakan klik tombol di bawah untuk mengirim konfirmasi dan rincian ke WhatsApp kedai kami.
                        </p>
                    </div>
                </div>

                <a href="{{ $waLink }}" target="_blank" class="w-full py-3.5 px-6 rounded-2xl bg-[#2E7D5B] hover:bg-[#256349] text-white font-black text-sm flex items-center justify-center gap-2 shadow-md transition-all text-center">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Konfirmasi Pesanan ke WhatsApp</span>
                </a>
            </div>
        @elseif($order->status === 'READY_FOR_PICKUP')
            <div class="p-6 rounded-2xl bg-[#FFF8EF] border-2 border-[#2E7D5B] space-y-3 text-center">
                <div class="w-12 h-12 rounded-full bg-[#2E7D5B] text-white flex items-center justify-center mx-auto font-bold">
                    ☕
                </div>
                <h3 class="text-lg font-black text-[#2E7D5B]">Kopi Anda Sudah Siap Diambil!</h3>
                <p class="text-xs text-[#6F4E37]">
                    Silakan tunjukkan kode pesanan <strong>{{ $order->order_code }}</strong> kepada kasir atau barista kami di kedai.
                </p>
            </div>
        @endif

        <!-- Items Snapshot Details -->
        <div class="text-left bg-[#FFF8EF]/50 p-6 rounded-3xl border border-[#D8B894]/40 space-y-4">
            <h4 class="text-xs font-bold text-[#3A2418] uppercase tracking-wider">Rincian Menu yang Dipesan:</h4>
            <div class="divide-y divide-[#D8B894]/30 text-xs">
                @foreach($order->items as $item)
                    <div class="py-2.5 flex justify-between items-center">
                        <div>
                            <span class="font-bold text-[#3A2418]">{{ $item->product_name_snapshot }}</span>
                            <span class="text-[#6F4E37] block">Qty: {{ $item->quantity }}x @ Rp{{ number_format($item->unit_price, 0, ',', '.') }}</span>
                        </div>
                        <span class="font-bold text-[#3A2418]">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="pt-3 border-t border-[#D8B894]/40 flex justify-between text-sm font-black text-[#3A2418]">
                <span>Total Pembayaran:</span>
                <span class="text-base text-[#2E7D5B]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>

            <div class="pt-2 text-[11px] text-[#6F4E37] space-y-1">
                <p><strong>Jadwal Pengambilan:</strong> {{ $order->pickup_at ?: '-' }}</p>
                @if($order->customer_notes)
                    <p><strong>Catatan:</strong> {{ $order->customer_notes }}</p>
                @endif
            </div>
        </div>

        <!-- Account / CTA Links -->
        @if(!Auth::guard('customer')->check())
            <div class="p-4 rounded-2xl bg-white border border-[#D8B894]/60 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <span class="text-[#6F4E37]">Ingin simpan pesanan ini agar tidak perlu cari-cari kode lagi?</span>
                <a href="{{ route('customer.register') }}" class="font-bold text-[#B9824A] hover:underline shrink-0">
                    Daftar Akun Pelanggan &rarr;
                </a>
            </div>
        @else
            <div class="text-xs text-[#6F4E37]">
                Pesanan ini terhubung dengan akun Anda.
                <a href="{{ route('customer.orders') }}" class="font-bold text-[#B9824A] hover:underline ml-1">Buka Semua Pesanan Saya &rarr;</a>
            </div>
        @endif

        <div class="pt-4 flex flex-wrap justify-center gap-3">
            <a href="{{ route('home') }}" class="px-6 py-2.5 rounded-2xl bg-[#F3E5D0] hover:bg-[#D8B894] text-[#3A2418] font-bold text-xs transition-all">
                Kembali ke Beranda
            </a>
            <a href="{{ route('menu') }}" class="px-6 py-2.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-xs transition-all">
                Pesan Menu Lain
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto refresh halaman jika status masih aktif (PENDING, CONFIRMED, atau PREPARING)
    @if(in_array($order->status, ['PENDING', 'CONFIRMED', 'PREPARING']))
        setTimeout(function() {
            window.location.reload();
        }, 15000); // refresh otomatis tiap 15 detik untuk update status real-time
    @endif
</script>
@endpush
