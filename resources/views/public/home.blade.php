@extends('layouts.public')

@section('title', 'KopiZ — Kopinya Gen-Z | Kopi Berkualitas, Harga Pas')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F3E5D0]/60 to-[#FFF8EF] py-16 lg:py-24 border-b border-[#D8B894]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#3A2418] text-[#FFF8EF] text-xs font-bold tracking-wide uppercase shadow-sm">
                    <span>☕</span>
                    <span>{{ $settings['brand_tagline'] ?? 'Kopinya Gen-Z' }}</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[#3A2418] leading-[1.15] tracking-tight">
                    {{ $settings['hero_title'] ?? 'Kopi Asli Indonesia, Rasa Pas di Hati & Kantong' }}
                </h1>
                <p class="text-base sm:text-lg text-[#6F4E37] font-medium leading-relaxed max-w-xl mx-auto lg:mx-0">
                    {{ $settings['hero_subtitle'] ?? 'Temani setiap sesi tugas, nongkrong, dan lemburmu dengan racikan kopi istimewa KopiZ.' }}
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                    <a href="{{ route('menu') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-base transition-all shadow-md text-center flex items-center justify-center gap-2">
                        <i data-lucide="coffee" class="w-5 h-5"></i>
                        <span>Lihat Menu</span>
                    </a>
                    <a href="{{ route('po') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-[#B9824A] hover:bg-[#B9824A]/90 text-white font-bold text-base transition-all shadow-md text-center flex items-center justify-center gap-2">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        <span>Pre-Order Spesial</span>
                    </a>
                </div>
                <!-- Mini Info Badges -->
                <div class="grid grid-cols-3 gap-3 pt-6 border-t border-[#D8B894]/40 text-center">
                    <div>
                        <span class="block text-xl font-black text-[#3A2418]">100%</span>
                        <span class="text-xs font-semibold text-[#6F4E37]">Biji Kopi Asli</span>
                    </div>
                    <div>
                        <span class="block text-xl font-black text-[#3A2418]">Rp15.000</span>
                        <span class="text-xs font-semibold text-[#6F4E37]">Mulai Dari</span>
                    </div>
                    <div>
                        <span class="block text-xl font-black text-[#3A2418]">Instant</span>
                        <span class="text-xs font-semibold text-[#6F4E37]">Order WhatsApp</span>
                    </div>
                </div>
            </div>

            <!-- Hero Image Banner -->
            <div class="relative">
                <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl border-4 border-[#FFF8EF] relative group">
                    <img src="https://images.unsplash.com/photo-1509785307050-d4066910ec1e?w=1000&auto=format&fit=crop&q=80" alt="KopiZ Signature Cup" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#3A2418]/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 p-5 rounded-2xl bg-[#FFF8EF]/95 backdrop-blur-md border border-[#D8B894] shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Menu Paling Dicari 🔥</span>
                                <span class="text-lg font-black text-[#3A2418] block">Butterscotch Signature</span>
                            </div>
                            <span class="text-lg font-extrabold text-[#3A2418] bg-[#F3E5D0] px-3.5 py-1.5 rounded-xl">Rp15.000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produk Unggulan Section -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-10">
        <div>
            <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Katalog Populer</span>
            <h2 class="text-3xl font-black text-[#3A2418] tracking-tight">Kopi Favorit Pelanggan</h2>
        </div>
        <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#6F4E37] hover:text-[#3A2418] transition-colors">
            <span>Lihat Semua Menu</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($featuredProducts as $product)
            <div class="bg-white rounded-3xl overflow-hidden border border-[#D8B894]/60 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="aspect-[4/3] overflow-hidden relative bg-[#F3E5D0]">
                    <img src="{{ $product->image_path ?: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&auto=format&fit=crop&q=80' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4">
                        @if($product->status === 'READY')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#2E7D5B] text-white shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                READY
                            </span>
                        @elseif($product->status === 'PO')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#B9824A] text-white shadow-sm">
                                PRE-ORDER
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#B93838] text-white shadow-sm">
                                HABIS
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                    <div>
                        <span class="text-xs font-semibold text-[#B9824A] uppercase tracking-wider block mb-1">
                            {{ $product->category ? $product->category->name : 'Signature Coffee' }}
                        </span>
                        <h3 class="text-xl font-bold text-[#3A2418] group-hover:text-[#B9824A] transition-colors">
                            <a href="{{ route('menu.detail', $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        <p class="text-sm text-[#6F4E37]/80 line-clamp-2 mt-2 leading-relaxed">
                            {{ $product->short_description ?: $product->description }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-[#D8B894]/30 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-[#6F4E37]/70 block font-medium">Harga</span>
                            <span class="text-xl font-black text-[#3A2418]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>

                        @if($product->status !== 'SOLD_OUT')
                            <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->image_path }}')" class="px-4 py-2.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-sm font-bold transition-all flex items-center gap-1.5 shadow-sm">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                <span>Pesan</span>
                            </button>
                        @else
                            <button disabled class="px-4 py-2.5 rounded-2xl bg-gray-200 text-gray-500 text-xs font-bold cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-[#6F4E37]">
                <p>Belum ada produk unggulan yang ditampilkan.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- PO Aktif Section -->
@if($activePreOrders->isNotEmpty())
<section class="py-16 bg-[#F3E5D0]/50 border-y border-[#D8B894]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Slot Terbatas</span>
            <h2 class="text-3xl font-black text-[#3A2418] tracking-tight">Pre-Order Spesial yang Sedang Dibuka</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($activePreOrders as $po)
                <div class="bg-white rounded-3xl overflow-hidden border border-[#D8B894] shadow-sm p-6 sm:p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <span class="px-3.5 py-1 rounded-full text-xs font-black bg-[#2E7D5B]/15 text-[#2E7D5B] border border-[#2E7D5B]/30">
                                SEDANG DIBUKA
                            </span>
                            <span class="text-xs font-bold text-[#6F4E37]">
                                Sisa Kuota: {{ $po->remainingQuota() }} / {{ $po->quota_units }} Unit
                            </span>
                        </div>
                        <h3 class="text-2xl font-black text-[#3A2418] mb-3">{{ $po->name }}</h3>
                        <p class="text-sm text-[#6F4E37] leading-relaxed mb-6">{{ $po->description }}</p>
                        
                        <div class="p-4 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/50 space-y-2 mb-6 text-xs text-[#3A2418]">
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-4 h-4 text-[#B9824A]"></i>
                                <span>Tutup PO: <strong>{{ $po->ends_at ? $po->ends_at->format('d M Y, H:i') . ' WIB' : 'Hingga kuota habis' }}</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="package-check" class="w-4 h-4 text-[#B9824A]"></i>
                                <span>Jadwal Pengambilan: <strong>{{ $po->pickup_at ? $po->pickup_at->format('d M Y, H:i') . ' WIB' : 'Konfirmasi via admin' }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#D8B894]/40 flex items-center justify-between">
                        <span class="text-sm font-semibold text-[#6F4E37]">{{ $po->products->count() }} Pilihan Menu</span>
                        <a href="{{ route('po.detail', $po->slug) }}" class="px-6 py-3 rounded-2xl bg-[#B9824A] hover:bg-[#3A2418] text-white text-sm font-bold transition-all shadow-md flex items-center gap-2">
                            <span>Ikuti Pre-Order</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Cara Pesan Singkat -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-xl mx-auto mb-12">
        <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Simpel & Cepat</span>
        <h2 class="text-3xl font-black text-[#3A2418] tracking-tight">Cara Pesan di KopiZ</h2>
        <p class="text-sm text-[#6F4E37] mt-2">Tanpa repot bikin akun atau install aplikasi tambahan</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="p-6 rounded-3xl bg-white border border-[#D8B894]/60 text-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-[#F3E5D0] text-[#3A2418] font-black text-lg flex items-center justify-center mx-auto">1</div>
            <h4 class="font-bold text-[#3A2418]">Pilih Menu</h4>
            <p class="text-xs text-[#6F4E37] leading-relaxed">Pilih kopi favoritmu dari katalog reguler atau paket Pre-Order.</p>
        </div>
        <div class="p-6 rounded-3xl bg-white border border-[#D8B894]/60 text-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-[#F3E5D0] text-[#3A2418] font-black text-lg flex items-center justify-center mx-auto">2</div>
            <h4 class="font-bold text-[#3A2418]">Checkout Instan</h4>
            <p class="text-xs text-[#6F4E37] leading-relaxed">Cukup isi nama dan nomor WhatsApp untuk data pemesan.</p>
        </div>
        <div class="p-6 rounded-3xl bg-white border border-[#D8B894]/60 text-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-[#F3E5D0] text-[#3A2418] font-black text-lg flex items-center justify-center mx-auto">3</div>
            <h4 class="font-bold text-[#3A2418]">Kirim ke WhatsApp</h4>
            <p class="text-xs text-[#6F4E37] leading-relaxed">Sistem membuat format pesan rapi yang siap dikirim langsung ke WhatsApp kami.</p>
        </div>
        <div class="p-6 rounded-3xl bg-white border border-[#D8B894]/60 text-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-[#F3E5D0] text-[#3A2418] font-black text-lg flex items-center justify-center mx-auto">4</div>
            <h4 class="font-bold text-[#3A2418]">Ambil Pesanan</h4>
            <p class="text-xs text-[#6F4E37] leading-relaxed">Kopi segar disiapkan dan siap kamu ambil di kedai KopiZ!</p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function addToCart(id, name, price, image) {
        KopiZCart.addItem({ id, name, price, image }, 1);
        alert(`Berhasil menambahkan "${name}" ke keranjang!`);
    }
</script>
@endpush
