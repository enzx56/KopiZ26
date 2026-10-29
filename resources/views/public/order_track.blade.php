@extends('layouts.public')

@section('title', 'Status Pesanan ' . $order->order_code . ' — KopiZ')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Success Banner -->
    <div class="bg-white rounded-3xl border border-[#D8B894] p-6 sm:p-10 shadow-sm text-center space-y-6">
        <div class="w-16 h-16 rounded-full bg-[#2E7D5B]/15 text-[#2E7D5B] flex items-center justify-center mx-auto">
            <i data-lucide="check-circle" class="w-9 h-9"></i>
        </div>

        <div>
            <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Pesanan Tersimpan</span>
            <h1 class="text-3xl font-black text-[#3A2418] tracking-tight mt-1">Kode Pesanan: {{ $order->order_code }}</h1>
            <p class="text-sm text-[#6F4E37] mt-2">
                Terima kasih, <strong>{{ $order->customer->name }}</strong>! Pesanan Anda telah tercatat dalam sistem kami.
            </p>
        </div>

        <!-- WhatsApp CTA (PRD Section 9 & 20) -->
        <div class="p-6 rounded-2xl bg-[#FFF8EF] border border-[#D8B894] space-y-4 text-left">
            <div class="flex items-start gap-3">
                <i data-lucide="message-circle" class="w-6 h-6 text-[#2E7D5B] shrink-0 mt-0.5"></i>
                <div>
                    <h3 class="text-base font-bold text-[#3A2418]">Kirim Rincian Pesanan ke WhatsApp KopiZ</h3>
                    <p class="text-xs text-[#6F4E37] mt-0.5 leading-relaxed">
                        Klik tombol hijau di bawah untuk membuka aplikasi WhatsApp dengan format pesanan lengkap yang sudah kami siapkan otomatis.
                    </p>
                </div>
            </div>

            <a href="{{ $waLink }}" target="_blank" class="w-full py-4 px-6 rounded-2xl bg-[#2E7D5B] hover:bg-[#2E7D5B]/90 text-white font-black text-sm flex items-center justify-center gap-2 shadow-md transition-all text-center">
                <i data-lucide="send" class="w-4 h-4"></i>
                <span>Buka WhatsApp & Kirim Pesanan Sekarang</span>
            </a>

            <div class="p-3 rounded-xl bg-white/70 border border-[#D8B894]/40 text-[11px] text-[#6F4E37] leading-relaxed">
                ⚠️ <strong>Pemberitahuan Penting:</strong> Menekan tombol WhatsApp hanya membuka aplikasi WhatsApp Anda. Pesanan akan diproses oleh barista setelah admin mengonfirmasi ketersediaan dan rincian transaksi Anda.
            </div>
        </div>

        <!-- Status Tracker Progress -->
        <div class="pt-6 border-t border-[#D8B894]/40 space-y-4">
            <div class="flex items-center justify-between text-xs font-bold text-[#3A2418]">
                <span>Status Saat Ini:</span>
                @php
                    $statusBadge = match($order->status) {
                        'PENDING' => ['bg-[#B7791F]/15', 'text-[#B7791F]', 'Menunggu Konfirmasi Admin'],
                        'CONFIRMED' => ['bg-[#2E7D5B]/15', 'text-[#2E7D5B]', 'Pesanan Telah Dikonfirmasi'],
                        'PREPARING' => ['bg-[#B9824A]/15', 'text-[#B9824A]', 'Sedang Diracik Barista'],
                        'READY_FOR_PICKUP' => ['bg-[#2E7D5B]/20', 'text-[#2E7D5B]', 'Siap Diambil di Kedai'],
                        'COMPLETED' => ['bg-[#3A2418]', 'text-white', 'Selesai'],
                        'CANCELLED' => ['bg-[#B93838]/15', 'text-[#B93838]', 'Dibatalkan'],
                        default => ['bg-gray-100', 'text-gray-700', $order->status]
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusBadge[0] }} {{ $statusBadge[1] }}">
                    {{ $statusBadge[2] }}
                </span>
            </div>

            <!-- Items Snapshot Details -->
            <div class="text-left bg-[#FFF8EF]/50 p-5 rounded-2xl border border-[#D8B894]/40 space-y-3">
                <h4 class="text-xs font-bold text-[#3A2418] uppercase tracking-wider">Rincian Menu:</h4>
                <div class="divide-y divide-[#D8B894]/30 text-xs">
                    @foreach($order->items as $item)
                        <div class="py-2 flex justify-between">
                            <span><strong>{{ $item->product_name_snapshot }}</strong> ({{ $item->quantity }}x)</span>
                            <span class="font-bold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="pt-3 border-t border-[#D8B894]/40 flex justify-between text-sm font-black text-[#3A2418]">
                    <span>Total Pembayaran:</span>
                    <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="pt-2 text-[11px] text-[#6F4E37] space-y-1">
                    <p><strong>Jadwal Pengambilan:</strong> {{ $order->pickup_at ?: '-' }}</p>
                    <p><strong>Catatan:</strong> {{ $order->customer_notes ?: '-' }}</p>
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-center gap-4">
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
