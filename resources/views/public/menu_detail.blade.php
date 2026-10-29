@extends('layouts.public')

@section('title', $product->name . ' — KopiZ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#6F4E37] hover:text-[#3A2418]">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start bg-white p-6 sm:p-10 rounded-3xl border border-[#D8B894]/60 shadow-sm">
        <!-- Foto Produk -->
        <div class="aspect-square rounded-3xl overflow-hidden bg-[#F3E5D0] relative border border-[#D8B894]/40">
            <img src="{{ $product->image_path ?: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&auto=format&fit=crop&q=80' }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            <div class="absolute top-4 left-4">
                @if($product->status === 'READY')
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#2E7D5B] text-white shadow-md">
                        <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                        READY STOCK
                    </span>
                @elseif($product->status === 'PO')
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#B9824A] text-white shadow-md">
                        PRE-ORDER
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#B93838] text-white shadow-md">
                        STOK HABIS
                    </span>
                @endif
            </div>
        </div>

        <!-- Detail Produk -->
        <div class="space-y-6">
            <div>
                <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block mb-1">
                    {{ $product->category ? $product->category->name : 'Signature Coffee' }}
                </span>
                <h1 class="text-3xl sm:text-4xl font-black text-[#3A2418]">{{ $product->name }}</h1>
                <p class="text-2xl font-black text-[#3A2418] mt-3">
                    Rp{{ number_format($product->price, 0, ',', '.') }}
                </p>
            </div>

            <div class="border-t border-b border-[#D8B894]/40 py-4 space-y-3">
                <h3 class="text-sm font-bold text-[#3A2418] uppercase tracking-wider">Deskripsi Racikan</h3>
                <p class="text-sm text-[#6F4E37] leading-relaxed whitespace-pre-line">
                    {{ $product->description ?: $product->short_description }}
                </p>
            </div>

            <!-- Kuantitas & Tombol Tambah -->
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-bold text-[#3A2418]">Kuantitas:</span>
                    <div class="flex items-center border border-[#D8B894] rounded-2xl bg-[#FFF8EF]">
                        <button type="button" onclick="adjustQty(-1)" class="w-10 h-10 flex items-center justify-center font-black text-[#3A2418] hover:bg-[#F3E5D0] rounded-l-2xl">-</button>
                        <input type="number" id="detail-qty" value="1" min="1" max="50" class="w-12 text-center text-sm font-bold bg-transparent focus:outline-none text-[#3A2418]" readonly>
                        <button type="button" onclick="adjustQty(1)" class="w-10 h-10 flex items-center justify-center font-black text-[#3A2418] hover:bg-[#F3E5D0] rounded-r-2xl">+</button>
                    </div>
                </div>

                @if($product->status !== 'SOLD_OUT')
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="addToCartDetail()" class="flex-1 py-4 px-6 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            <span>Masukkan ke Keranjang</span>
                        </button>
                        <button onclick="buyNow()" class="sm:w-auto py-4 px-6 rounded-2xl bg-[#B9824A] hover:bg-[#B9824A]/90 text-white font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2">
                            <span>Langsung Checkout</span>
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-[#B93838]/10 border border-[#B93838]/30 text-[#B93838] text-sm font-bold text-center">
                        Menu ini saat ini sedang habis. Silakan periksa kembali nanti!
                    </div>
                @endif
            </div>

            <div class="p-4 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/50 flex items-center gap-3 text-xs text-[#6F4E37]">
                <i data-lucide="shield-check" class="w-5 h-5 text-[#2E7D5B] shrink-0"></i>
                <span>Disiapkan fresh dengan biji kopi pilihan dan higienis langsung di kedai KopiZ.</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function adjustQty(amount) {
        const input = document.getElementById('detail-qty');
        let current = parseInt(input.value) || 1;
        input.value = Math.max(1, current + amount);
    }

    function addToCartDetail() {
        const qty = parseInt(document.getElementById('detail-qty').value) || 1;
        KopiZCart.addItem({
            id: {{ $product->id }},
            name: '{{ addslashes($product->name) }}',
            price: {{ $product->price }},
            image: '{{ $product->image_path }}'
        }, qty);
        alert(`Berhasil menambahkan ${qty}x "{{ $product->name }}" ke keranjang!`);
    }

    function buyNow() {
        addToCartDetail();
        window.location.href = "{{ route('checkout') }}";
    }
</script>
@endpush
