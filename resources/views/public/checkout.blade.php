@extends('layouts.public')

@section('title', 'Checkout Pesanan — KopiZ')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Langkah Terakhir</span>
        <h1 class="text-3xl font-black text-[#3A2418] tracking-tight">Checkout Pesanan</h1>
        <p class="text-sm text-[#6F4E37] mt-1">Isi data pemesan untuk konfirmasi pesanan KopiZ Anda.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" class="space-y-8">
        @csrf
        <input type="hidden" name="order_type" value="{{ $preOrder ? 'PRE_ORDER' : 'REGULAR' }}">
        @if($preOrder)
            <input type="hidden" name="pre_order_id" value="{{ $preOrder->id }}">
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left: Form Pemesan -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-5">
                    <h3 class="text-lg font-black text-[#3A2418] flex items-center gap-2">
                        <i data-lucide="user" class="w-5 h-5 text-[#B9824A]"></i>
                        <span>Informasi Pelanggan</span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Nama Lengkap *</label>
                        <input type="text" name="customer_name" required placeholder="Contoh: Rian Pratama" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Nomor WhatsApp Aktif *</label>
                        <input type="text" name="customer_phone" required placeholder="Contoh: 081234567890" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                        <span class="text-[11px] text-[#6F4E37] mt-1 block">Nomor ini akan digunakan untuk mengirimkan rincian pesanan dan konfirmasi.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Username Instagram (Opsional)</label>
                        <input type="text" name="instagram_handle" placeholder="Contoh: @rianpratama" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Rencana Waktu Pengambilan *</label>
                        <input type="text" name="pickup_at" value="{{ $preOrder ? $preOrder->pickup_at?->format('d M Y, H:i') : 'Hari ini (15-30 menit lagi)' }}" required class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="customer_notes" rows="3" placeholder="Contoh: Less sugar, extra ice, sedotan ramah lingkungan..." class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]"></textarea>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" required checked class="mt-1 rounded text-[#3A2418] focus:ring-[#B9824A]">
                            <span class="text-xs text-[#6F4E37] leading-relaxed">
                                Saya menyetujui <a href="{{ route('terms') }}" target="_blank" class="font-bold underline text-[#3A2418]">Syarat & Ketentuan</a> serta <a href="{{ route('privacy') }}" target="_blank" class="font-bold underline text-[#3A2418]">Kebijakan Privasi</a> KopiZ.
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right: Ringkasan Order & Hidden Inputs -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4">
                    <h3 class="text-base font-black text-[#3A2418]">Ringkasan Item</h3>

                    <div id="checkout-items-list" class="space-y-3 divide-y divide-[#D8B894]/30 text-xs">
                        <!-- Injected by JS -->
                    </div>

                    <!-- Hidden Inputs Container for Server Submission -->
                    <div id="checkout-hidden-inputs"></div>

                    <div class="pt-4 border-t border-[#D8B894]/40 space-y-2 text-xs">
                        <div class="flex justify-between text-[#6F4E37]">
                            <span>Subtotal:</span>
                            <span id="checkout-subtotal" class="font-bold text-[#3A2418]">Rp0</span>
                        </div>
                        <div class="flex justify-between text-[#6F4E37]">
                            <span>Biaya Layanan:</span>
                            <span class="font-bold text-[#2E7D5B]">GRATIS (Rp0)</span>
                        </div>
                        <div class="flex justify-between text-sm font-black text-[#3A2418] pt-2 border-t border-[#D8B894]/30">
                            <span>Total Pesanan:</span>
                            <span id="checkout-total" class="text-base text-[#3A2418]">Rp0</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-black text-sm shadow-md transition-all flex items-center justify-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Proses Pesanan Sekarang</span>
                    </button>

                    <div class="p-3 rounded-xl bg-[#FFF8EF] border border-[#D8B894]/50 text-[11px] text-[#6F4E37] leading-relaxed">
                        💡 <strong>Catatan:</strong> Setelah menekan tombol proses, sistem akan membuat Kode Pesanan unik resmi dan mengarahkan Anda ke WhatsApp KopiZ untuk konfirmasi langsung.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const items = KopiZCart.getItems();
        const listContainer = document.getElementById('checkout-items-list');
        const hiddenContainer = document.getElementById('checkout-hidden-inputs');
        const subtotalEl = document.getElementById('checkout-subtotal');
        const totalEl = document.getElementById('checkout-total');

        if (!items || items.length === 0) {
            alert('Keranjang belanja Anda kosong! Silakan pilih menu terlebih dahulu.');
            window.location.href = "{{ route('menu') }}";
            return;
        }

        let html = '';
        let hiddenHtml = '';
        let total = 0;

        items.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            html += `
                <div class="pt-2 flex justify-between items-center">
                    <div>
                        <span class="font-bold text-[#3A2418] block">${item.name}</span>
                        <span class="text-[#6F4E37]">${item.quantity}x @ Rp${item.price.toLocaleString('id-ID')}</span>
                    </div>
                    <span class="font-bold text-[#3A2418]">Rp${itemTotal.toLocaleString('id-ID')}</span>
                </div>
            `;

            hiddenHtml += `
                <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            `;
        });

        listContainer.innerHTML = html;
        hiddenContainer.innerHTML = hiddenHtml;
        subtotalEl.innerText = 'Rp' + total.toLocaleString('id-ID');
        totalEl.innerText = 'Rp' + total.toLocaleString('id-ID');

        // Clear cart after submitting
        document.getElementById('checkout-form').addEventListener('submit', () => {
            setTimeout(() => {
                KopiZCart.clear();
            }, 100);
        });
    });
</script>
@endpush
