@extends('layouts.public')

@section('title', 'Keranjang Belanja — KopiZ')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Pesanan Anda</span>
        <h1 class="text-3xl font-black text-[#3A2418] tracking-tight">Keranjang Belanja</h1>
    </div>

    <div id="cart-empty" class="hidden bg-white p-12 rounded-3xl border border-[#D8B894]/60 text-center space-y-4">
        <div class="w-16 h-16 rounded-full bg-[#F3E5D0] text-[#3A2418] flex items-center justify-center mx-auto text-2xl">
            ☕
        </div>
        <h3 class="text-xl font-bold text-[#3A2418]">Keranjang Anda Masih Kosong</h3>
        <p class="text-sm text-[#6F4E37] max-w-sm mx-auto">Yuk pilih racikan kopi nikmat favoritmu dari katalog sekarang juga!</p>
        <a href="{{ route('menu') }}" class="inline-block px-8 py-3.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-sm shadow-md transition-all">
            Lihat Menu Kopi
        </a>
    </div>

    <div id="cart-content" class="space-y-6">
        <!-- List Item -->
        <div class="bg-white rounded-3xl border border-[#D8B894]/60 overflow-hidden divide-y divide-[#D8B894]/30 shadow-sm" id="cart-items-container">
            <!-- Dynamic inject items via JS -->
        </div>

        <!-- Summary & Next Step -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4">
            <div class="flex items-center justify-between text-sm">
                <span class="font-bold text-[#6F4E37]">Estimasi Subtotal:</span>
                <span id="cart-subtotal" class="font-black text-xl text-[#3A2418]">Rp0</span>
            </div>
            <p class="text-xs text-[#6F4E37]/80">
                * Harga akhir dan ketersediaan stok akan diverifikasi secara ketat dan aman di server saat checkout.
            </p>

            <div class="pt-4 border-t border-[#D8B894]/40 flex flex-col sm:flex-row items-center justify-between gap-4">
                <button type="button" onclick="KopiZCart.clear(); renderCart();" class="text-xs font-bold text-[#B93838] hover:underline">
                    Kosongkan Keranjang
                </button>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('menu') }}" class="w-1/2 sm:w-auto px-6 py-3.5 rounded-2xl bg-[#F3E5D0] text-[#3A2418] hover:bg-[#D8B894] font-bold text-sm text-center transition-all">
                        Tambah Menu
                    </a>
                    <a href="{{ route('checkout') }}" class="w-1/2 sm:w-auto px-8 py-3.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-sm text-center shadow-md transition-all">
                        Lanjut ke Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function renderCart() {
        const items = KopiZCart.getItems();
        const emptyEl = document.getElementById('cart-empty');
        const contentEl = document.getElementById('cart-content');
        const container = document.getElementById('cart-items-container');
        const subtotalEl = document.getElementById('cart-subtotal');

        if (!items || items.length === 0) {
            emptyEl.classList.remove('hidden');
            contentEl.classList.add('hidden');
            return;
        }

        emptyEl.classList.add('hidden');
        contentEl.classList.remove('hidden');

        let html = '';
        let total = 0;

        items.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            html += `
                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <img src="${item.image || 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&auto=format&fit=crop&q=80'}" alt="${item.name}" class="w-16 h-16 rounded-2xl object-cover bg-[#F3E5D0] shrink-0">
                        <div>
                            <h4 class="font-bold text-[#3A2418] text-base">${item.name}</h4>
                            <span class="text-xs text-[#6F4E37] block font-medium">Rp${item.price.toLocaleString('id-ID')} / porsi</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-6">
                        <div class="flex items-center border border-[#D8B894] rounded-2xl bg-[#FFF8EF]">
                            <button type="button" onclick="KopiZCart.updateQuantity(${item.id}, ${item.quantity - 1}); renderCart();" class="w-8 h-8 flex items-center justify-center font-bold text-[#3A2418] hover:bg-[#F3E5D0] rounded-l-2xl">-</button>
                            <span class="w-10 text-center text-xs font-bold text-[#3A2418]">${item.quantity}</span>
                            <button type="button" onclick="KopiZCart.updateQuantity(${item.id}, ${item.quantity + 1}); renderCart();" class="w-8 h-8 flex items-center justify-center font-bold text-[#3A2418] hover:bg-[#F3E5D0] rounded-r-2xl">+</button>
                        </div>
                        <span class="font-black text-base text-[#3A2418] min-w-[90px] text-right">Rp${itemTotal.toLocaleString('id-ID')}</span>
                        <button type="button" onclick="KopiZCart.removeItem(${item.id}); renderCart();" class="text-gray-400 hover:text-[#B93838] transition-colors p-1" title="Hapus">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        subtotalEl.innerText = 'Rp' + total.toLocaleString('id-ID');
        lucide.createIcons();
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCart();
    });
</script>
@endpush
