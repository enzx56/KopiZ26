@extends('layouts.public')

@section('title', 'Status Pesanan ' . $order->order_code . ' — KopiZ')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl border border-[#D8B894] p-6 sm:p-10 shadow-sm text-center space-y-6">
        @php
            $isPending = $order->status === 'PENDING';
            $isConfirmed = in_array($order->status, ['CONFIRMED', 'PREPARING', 'COMPLETED']);
            $isPreparing = in_array($order->status, ['PREPARING', 'COMPLETED']);
            $isCompleted = $order->status === 'COMPLETED';
            $isCancelled = $order->status === 'CANCELLED';

            $statusBadge = match($order->status) {
                'PENDING' => ['bg-[#B7791F]/15', 'text-[#B7791F]', 'Menunggu Konfirmasi WhatsApp'],
                'CONFIRMED' => ['bg-[#2E7D5B]/15', 'text-[#2E7D5B]', 'Pesanan Dikonfirmasi ✓'],
                'PREPARING' => ['bg-[#B9824A]/15', 'text-[#B9824A]', 'Sedang Diracik Barista ☕'],
                'COMPLETED' => ['bg-[#3A2418]', 'text-white', 'Selesai — Selamat Menikmati!'],
                'CANCELLED' => ['bg-[#B93838]/15', 'text-[#B93838]', 'Pesanan Dibatalkan'],
                default => ['bg-gray-100', 'text-gray-700', $order->status]
            };
        @endphp

        <!-- Icon Header -->
        <div class="w-16 h-16 rounded-full {{ $statusBadge[0] }} {{ $statusBadge[1] }} flex items-center justify-center mx-auto text-2xl">
            @if($isCancelled)
                <i data-lucide="x-circle" class="w-9 h-9"></i>
            @elseif($isPreparing)
                <i data-lucide="coffee" class="w-9 h-9"></i>
            @elseif($isCompleted)
                <i data-lucide="check" class="w-9 h-9"></i>
            @else
                <i data-lucide="check-circle" class="w-9 h-9"></i>
            @endif
        </div>

        <div>
            <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Pelacakan Pesanan KopiZ</span>
            <h1 class="text-3xl font-black text-[#3A2418] tracking-tight mt-1">Kode: {{ $order->order_code }}</h1>
            <p class="text-sm text-[#6F4E37] mt-2">
                Halo <strong>{{ $order->customer->name }}</strong>, pesanan Anda diproses langsung melalui WhatsApp KopiZ.
            </p>
            <div class="mt-3">
                <span class="inline-block px-4 py-1.5 rounded-full text-xs font-black {{ $statusBadge[0] }} {{ $statusBadge[1] }}">
                    {{ $statusBadge[2] }}
                </span>
            </div>
        </div>

        <!-- Progress Tracker Bar (4 Tahap Sederhana) -->
        @if(!$isCancelled)
            <div class="p-6 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/60">
                <div class="grid grid-cols-4 gap-2 text-center text-[10px] sm:text-xs">
                    <!-- Step 1: Dipesan -->
                    <div class="space-y-1">
                        <div class="w-8 h-8 rounded-full bg-[#2E7D5B] text-white flex items-center justify-center mx-auto font-bold shadow-sm">
                            ✓
                        </div>
                        <span class="font-bold text-[#3A2418] block">Dipesan</span>
                    </div>

                    <!-- Step 2: Dikonfirmasi WA -->
                    <div class="space-y-1">
                        <div class="w-8 h-8 rounded-full {{ $isConfirmed ? 'bg-[#2E7D5B] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center mx-auto font-bold shadow-sm">
                            {{ $isConfirmed ? '✓' : '2' }}
                        </div>
                        <span class="font-bold {{ $isConfirmed ? 'text-[#3A2418]' : 'text-gray-400' }} block">Konfirmasi WA</span>
                    </div>

                    <!-- Step 3: Diracik -->
                    <div class="space-y-1">
                        <div class="w-8 h-8 rounded-full {{ $isPreparing ? 'bg-[#2E7D5B] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center mx-auto font-bold shadow-sm">
                            {{ $isPreparing ? '✓' : '3' }}
                        </div>
                        <span class="font-bold {{ $isPreparing ? 'text-[#3A2418]' : 'text-gray-400' }} block">Diracik</span>
                    </div>

                    <!-- Step 4: Selesai -->
                    <div class="space-y-1">
                        <div class="w-8 h-8 rounded-full {{ $isCompleted ? 'bg-[#3A2418] text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center mx-auto font-bold shadow-sm">
                            {{ $isCompleted ? '✓' : '4' }}
                        </div>
                        <span class="font-bold {{ $isCompleted ? 'text-[#3A2418]' : 'text-gray-400' }} block">Selesai</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- WhatsApp Action Card (Kirim Pesanan ke WhatsApp) -->
        <div class="p-6 rounded-2xl bg-[#E8F5E9] border border-[#2E7D5B]/30 space-y-4 text-left">
            <div class="flex items-start gap-3">
                <i data-lucide="message-circle" class="w-6 h-6 text-[#2E7D5B] shrink-0 mt-0.5"></i>
                <div>
                    <h3 class="text-base font-bold text-[#2E7D5B]">Kirim / Chat Pesanan ke WhatsApp KopiZ</h3>
                    <p class="text-xs text-[#2E7D5B]/90 mt-0.5 leading-relaxed">
                        Klik tombol di bawah untuk langsung membuka WhatsApp dengan format pesanan lengkap yang sudah dibuat otomatis.
                    </p>
                </div>
            </div>

            <a href="{{ $waLink }}" target="_blank" class="w-full py-3.5 px-6 rounded-2xl bg-[#2E7D5B] hover:bg-[#256349] text-white font-black text-sm flex items-center justify-center gap-2 shadow-md transition-all text-center">
                <i data-lucide="send" class="w-4 h-4"></i>
                <span>Buka WhatsApp & Kirim Pesanan Sekarang</span>
            </a>
        </div>

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
                <span>Total Pesanan:</span>
                <span class="text-base text-[#2E7D5B]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>

            @if($order->customer_notes)
                <div class="pt-2 text-[11px] text-[#6F4E37]">
                    <p><strong>Catatan:</strong> {{ $order->customer_notes }}</p>
                </div>
            @endif
        </div>

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
        }, 15000);
    @endif
</script>
@endpush
