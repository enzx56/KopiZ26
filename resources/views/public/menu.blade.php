@extends('layouts.public')

@section('title', 'Katalog Menu KopiZ — Kopinya Gen-Z')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Katalog Lengkap</span>
        <h1 class="text-3xl font-black text-[#3A2418] tracking-tight">Menu Kopi KopiZ</h1>
        <p class="text-sm text-[#6F4E37] mt-1">Eksplorasi racikan kopi berkualitas dengan harga yang pas di kantong.</p>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-3xl border border-[#D8B894]/60 shadow-sm mb-10">
        <form method="GET" action="{{ route('menu') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            <!-- Search -->
            <div class="md:col-span-5 relative">
                <i data-lucide="search" class="w-4 h-4 text-[#6F4E37]/60 absolute left-4 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama kopi..." class="w-full pl-11 pr-4 py-2.5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/50 focus:outline-none focus:border-[#B9824A] text-sm text-[#3A2418]">
            </div>

            <!-- Category -->
            <div class="md:col-span-3">
                <select name="category" class="w-full px-4 py-2.5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/50 focus:outline-none focus:border-[#B9824A] text-sm text-[#3A2418]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort -->
            <div class="md:col-span-2">
                <select name="sort" class="w-full px-4 py-2.5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/50 focus:outline-none focus:border-[#B9824A] text-sm text-[#3A2418]">
                    <option value="">Urutan Default</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A - Z</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-sm font-bold transition-all text-center">
                    Terapkan
                </button>
                @if(request()->hasAny(['q', 'category', 'sort']))
                    <a href="{{ route('menu') }}" class="px-3 py-2.5 rounded-2xl bg-[#F3E5D0] text-[#3A2418] hover:bg-[#D8B894] transition-all flex items-center justify-center" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Grid Produk -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($products as $product)
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
            <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-[#D8B894]/40 p-8">
                <i data-lucide="coffee" class="w-12 h-12 text-[#B9824A] mx-auto mb-3"></i>
                <h3 class="text-lg font-bold text-[#3A2418]">Menu tidak ditemukan</h3>
                <p class="text-sm text-[#6F4E37] mt-1">Coba gunakan kata kunci pencarian lain atau pilih kategori berbeda.</p>
                <a href="{{ route('menu') }}" class="inline-block mt-4 px-6 py-2 rounded-xl bg-[#3A2418] text-white text-xs font-bold">Lihat Semua Menu</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $products->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addToCart(id, name, price, image) {
        KopiZCart.addItem({ id, name, price, image }, 1);
        alert(`Berhasil menambahkan "${name}" ke keranjang!`);
    }
</script>
@endpush
