@extends('layouts.public')

@section('title', 'Pre-Order Kopi Spesial — KopiZ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-10 text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Program Eksklusif</span>
        <h1 class="text-3xl sm:text-4xl font-black text-[#3A2418] tracking-tight">Sistem Pre-Order (PO) KopiZ</h1>
        <p class="text-sm text-[#6F4E37] mt-2 leading-relaxed">
            Dapatkan racikan kopi spesial botol literan atau menu terbatas dengan kuota terbatas dan harga lebih hemat.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($preOrders as $po)
            <div class="bg-white rounded-3xl overflow-hidden border border-[#D8B894] shadow-sm flex flex-col justify-between group hover:shadow-xl transition-all duration-300">
                <div class="aspect-[16/9] overflow-hidden relative bg-[#F3E5D0]">
                    <img src="{{ $po->banner_path ?: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1200&auto=format&fit=crop&q=80' }}" alt="{{ $po->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4">
                        @if($po->status === 'OPEN')
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#2E7D5B] text-white shadow-md">
                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                DIBUKA
                            </span>
                        @elseif($po->status === 'SCHEDULED')
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#B7791F] text-white shadow-md">
                                SEGERA DIBUKA
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#B93838] text-white shadow-md">
                                KUOTA PENUH / DITUTUP
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6 sm:p-8 flex-grow flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-[#6F4E37] mb-2">
                            <span>Sisa Kuota: {{ $po->remainingQuota() }} Unit</span>
                            <span>Total Kuota: {{ $po->quota_units }} Unit</span>
                        </div>
                        <!-- Progress Bar Kuota -->
                        <div class="w-full h-2.5 rounded-full bg-[#F3E5D0] overflow-hidden mb-4">
                            @php
                                $percent = $po->quota_units > 0 ? min(100, round(($po->reserved_units / $po->quota_units) * 100)) : 0;
                            @endphp
                            <div class="h-full bg-[#B9824A] rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                        </div>

                        <h2 class="text-2xl font-black text-[#3A2418] mb-3 group-hover:text-[#B9824A] transition-colors">
                            <a href="{{ route('po.detail', $po->slug) }}">{{ $po->name }}</a>
                        </h2>
                        <p class="text-sm text-[#6F4E37] leading-relaxed line-clamp-3">
                            {{ $po->description }}
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/50 space-y-2 text-xs text-[#3A2418]">
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-[#B9824A]"></i>
                            <span>Batas PO: <strong>{{ $po->ends_at ? $po->ends_at->format('d M Y, H:i') . ' WIB' : 'Hingga kuota habis' }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="package-check" class="w-4 h-4 text-[#B9824A]"></i>
                            <span>Jadwal Pengambilan: <strong>{{ $po->pickup_at ? $po->pickup_at->format('d M Y, H:i') . ' WIB' : 'Konfirmasi via admin' }}</strong></span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#D8B894]/40 flex items-center justify-between">
                        <span class="text-sm font-semibold text-[#6F4E37]">{{ $po->products->count() }} Pilihan Menu PO</span>
                        @if($po->isOpen())
                            <a href="{{ route('po.detail', $po->slug) }}" class="px-6 py-3 rounded-2xl bg-[#B9824A] hover:bg-[#3A2418] text-white text-sm font-bold transition-all shadow-md flex items-center gap-2">
                                <span>Pesan Sekarang</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        @else
                            <a href="{{ route('po.detail', $po->slug) }}" class="px-6 py-3 rounded-2xl bg-[#F3E5D0] text-[#3A2418] text-sm font-bold transition-all flex items-center gap-2">
                                <span>Lihat Detail</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-[#D8B894]/40 p-8">
                <i data-lucide="calendar-x" class="w-12 h-12 text-[#B9824A] mx-auto mb-3"></i>
                <h3 class="text-lg font-bold text-[#3A2418]">Belum Ada Batch Pre-Order Terbuka</h3>
                <p class="text-sm text-[#6F4E37] mt-1">Nantikan batch pre-order berikutnya di media sosial KopiZ.</p>
                <a href="{{ route('menu') }}" class="inline-block mt-4 px-6 py-2.5 rounded-2xl bg-[#3A2418] text-white text-xs font-bold">Lihat Menu Reguler</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
