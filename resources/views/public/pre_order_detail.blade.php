@extends('layouts.public')

@section('title', $preOrder->name . ' — Pre-Order KopiZ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <a href="{{ route('po') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#6F4E37] hover:text-[#3A2418]">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Pre-Order</span>
        </a>
    </div>

    <!-- Banner & Header -->
    <div class="bg-white rounded-3xl overflow-hidden border border-[#D8B894] shadow-sm mb-10">
        <div class="aspect-[21/9] max-h-80 overflow-hidden relative bg-[#F3E5D0]">
            <img src="{{ $preOrder->banner_path ?: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1200&auto=format&fit=crop&q=80' }}" alt="{{ $preOrder->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#3A2418]/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-6 left-6 right-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div class="text-white">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $preOrder->isOpen() ? 'bg-[#2E7D5B]' : 'bg-[#B93838]' }} mb-2 inline-block">
                        {{ $preOrder->isOpen() ? 'OPEN PRE-ORDER' : 'PRE-ORDER DITUTUP / PENUH' }}
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-black">{{ $preOrder->name }}</h1>
                </div>
                <div class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-2xl text-xs font-bold text-[#3A2418]">
                    Sisa Kuota: {{ $preOrder->remainingQuota() }} / {{ $preOrder->quota_units }} Unit
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <div>
                <h3 class="text-sm font-bold text-[#3A2418] uppercase tracking-wider mb-2">Tentang Batch Ini</h3>
                <p class="text-sm text-[#6F4E37] leading-relaxed">{{ $preOrder->description }}</p>
            </div>

            <!-- Detail Jadwal & Lokasi -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/50 text-xs text-[#3A2418]">
                <div>
                    <span class="font-bold text-[#6F4E37] block">Batas Waktu Order:</span>
                    <span class="font-black text-sm">{{ $preOrder->ends_at ? $preOrder->ends_at->format('d M Y, H:i') . ' WIB' : 'Hingga kuota habis' }}</span>
                </div>
                <div>
                    <span class="font-bold text-[#6F4E37] block">Jadwal Pengambilan:</span>
                    <span class="font-black text-sm">{{ $preOrder->pickup_at ? $preOrder->pickup_at->format('d M Y, H:i') . ' WIB' : 'Sesuai info kedai' }}</span>
                </div>
                <div>
                    <span class="font-bold text-[#6F4E37] block">Instruksi:</span>
                    <span>{{ $preOrder->instruction_notes ?: 'Tunjukkan kode pesanan kepada barista kami saat pickup.' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilihan Menu PO -->
    <div class="mb-10">
        <h2 class="text-2xl font-black text-[#3A2418] mb-6">Pilih Menu dalam Pre-Order Ini</h2>

        @if($preOrder->isOpen())
            <form action="{{ route('checkout.process') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="order_type" value="PRE_ORDER">
                <input type="hidden" name="pre_order_id" value="{{ $preOrder->id }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($preOrder->products as $index => $prod)
                        <div class="bg-white rounded-3xl p-6 border border-[#D8B894] shadow-sm flex flex-col justify-between space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-[#F3E5D0] overflow-hidden shrink-0">
                                    <img src="{{ $prod->image_path ?: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&auto=format&fit=crop&q=80' }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-[#3A2418]">{{ $prod->name }}</h4>
                                    <span class="text-sm font-black text-[#B9824A]">Rp{{ number_format($prod->pivot->price ?: $prod->price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $prod->id }}">

                            <div class="pt-4 border-t border-[#D8B894]/30 flex items-center justify-between">
                                <label class="text-xs font-bold text-[#6F4E37]">Jumlah Pesanan:</label>
                                <input type="number" name="items[{{ $index }}][quantity]" value="{{ $index === 0 ? 1 : 0 }}" min="0" max="{{ min(10, $preOrder->remainingQuota()) }}" class="w-20 px-3 py-1.5 rounded-xl border border-[#D8B894] text-center font-bold text-sm focus:outline-none focus:border-[#B9824A]">
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Data Pemesan Checkout Cepat -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#D8B894] shadow-sm space-y-4">
                    <h3 class="text-lg font-black text-[#3A2418]">Data Pemesan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#3A2418] mb-1">Nama Lengkap *</label>
                            <input type="text" name="customer_name" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-2.5 rounded-2xl border border-[#D8B894] text-sm focus:outline-none focus:border-[#B9824A]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#3A2418] mb-1">Nomor WhatsApp *</label>
                            <input type="text" name="customer_phone" required placeholder="Contoh: 081234567890" class="w-full px-4 py-2.5 rounded-2xl border border-[#D8B894] text-sm focus:outline-none focus:border-[#B9824A]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="customer_notes" rows="2" placeholder="Catatan rasa, gula, atau pesan khusus..." class="w-full px-4 py-2 rounded-2xl border border-[#D8B894] text-sm focus:outline-none focus:border-[#B9824A]"></textarea>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-xs text-[#6F4E37]">Total akan dihitung ulang secara aman di server saat Anda menekan tombol pesan.</span>
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-sm transition-all shadow-md">
                            Konfirmasi & Kirim Pesanan PO
                        </button>
                    </div>
                </div>
            </form>
        @else
            <div class="p-8 rounded-3xl bg-white border border-[#D8B894]/60 text-center">
                <i data-lucide="lock" class="w-10 h-10 text-[#B93838] mx-auto mb-3"></i>
                <h3 class="text-lg font-bold text-[#3A2418]">Pre-Order Ini Tidak Menerima Pesanan Baru</h3>
                <p class="text-sm text-[#6F4E37] mt-1">Kuota telah habis atau periode pemesanan telah selesai.</p>
                <a href="{{ route('po') }}" class="inline-block mt-4 px-6 py-2 rounded-xl bg-[#3A2418] text-white text-xs font-bold">Kembali ke Daftar PO</a>
            </div>
        @endif
    </div>
</div>
@endsection
