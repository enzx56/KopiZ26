@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_code . ' — KopiZ Admin')
@section('header_title', 'Detail Pesanan: ' . $order->order_code)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#6F4E37] hover:text-[#3A2418] flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Pesanan</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="px-4 py-2 rounded-xl bg-[#F3E5D0] hover:bg-[#D8B894] text-[#3A2418] text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak Struk / Ringkasan</span>
            </a>
            <a href="{{ route('order.track', $order->order_code) }}" target="_blank" class="px-4 py-2 rounded-xl bg-[#3A2418] text-white text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Lihat Status Publik</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Rincian Order Items & Data Pelanggan (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Pelanggan & Info Transaksi -->
            <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4">
                <h3 class="text-base font-black text-[#3A2418]">Informasi Pelanggan & Jadwal</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-[#6F4E37] block">Nama Pelanggan:</span>
                        <span class="font-bold text-sm text-[#3A2418]">{{ $order->customer->name }}</span>
                    </div>
                    <div>
                        <span class="text-[#6F4E37] block">Nomor WhatsApp:</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer->phone) }}" target="_blank" class="font-bold text-sm text-[#2E7D5B] hover:underline flex items-center gap-1">
                            <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                            <span>{{ $order->customer->phone }}</span>
                        </a>
                    </div>
                    <div>
                        <span class="text-[#6F4E37] block">Waktu Pengambilan (Pickup):</span>
                        <span class="font-bold text-sm text-[#3A2418]">{{ $order->pickup_at ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-[#6F4E37] block">Jenis Pesanan:</span>
                        <span class="font-bold text-sm text-[#B9824A]">{{ $order->order_type }}</span>
                        @if($order->preOrder)
                            <span class="block text-[11px] text-[#6F4E37]">Batch: {{ $order->preOrder->name }}</span>
                        @endif
                    </div>
                </div>

                @if($order->customer_notes)
                    <div class="p-3.5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/40 text-xs">
                        <span class="font-bold text-[#3A2418] block mb-0.5">Catatan Pelanggan:</span>
                        <p class="text-[#6F4E37]">{{ $order->customer_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Rincian Item (Snapshot PRD Section 15 & 20) -->
            <div class="bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#D8B894]/40">
                    <h3 class="text-base font-black text-[#3A2418]">Item Pesanan (Snapshot Transaksi)</h3>
                    <p class="text-[11px] text-[#6F4E37]">Harga tersimpan saat transaksi dan tidak terpengaruh perubahan harga produk di masa depan.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#FFF8EF] border-b border-[#D8B894]/40 text-[#6F4E37] uppercase font-bold tracking-wider">
                            <tr>
                                <th class="py-3 px-4">Menu Kopi</th>
                                <th class="py-3 px-4 text-center">Harga Satuan</th>
                                <th class="py-3 px-4 text-center">Kuantitas</th>
                                <th class="py-3 px-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#D8B894]/30">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-[#FFF8EF]/30">
                                    <td class="py-3 px-4 font-bold text-[#3A2418] text-sm">
                                        {{ $item->product_name_snapshot }}
                                        @if($item->sku_snapshot)
                                            <span class="block text-[10px] font-mono text-[#6F4E37] font-normal">SKU: {{ $item->sku_snapshot }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center font-medium text-[#6F4E37]">
                                        Rp{{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-center font-bold text-[#3A2418]">
                                        {{ $item->quantity }}x
                                    </td>
                                    <td class="py-3 px-4 text-right font-black text-[#3A2418]">
                                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-[#FFF8EF]/50 border-t border-[#D8B894]/40 font-black text-sm text-[#3A2418]">
                            <tr>
                                <td colspan="3" class="py-4 px-4 text-right">TOTAL AKHIR:</td>
                                <td class="py-4 px-4 text-right text-base text-[#2E7D5B]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Riwayat Perubahan Status (PRD Section 12) -->
            <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4">
                <h3 class="text-base font-black text-[#3A2418]">Riwayat Status Pesanan (Audit Trail)</h3>
                <div class="space-y-3">
                    @forelse($order->statusHistories as $hist)
                        <div class="p-3 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/30 text-xs flex items-start justify-between gap-4">
                            <div>
                                <span class="font-bold text-[#3A2418]">
                                    {{ $hist->from_status ?: 'BARU' }} &rarr; <span class="text-[#B9824A]">{{ $hist->to_status }}</span>
                                </span>
                                <p class="text-[#6F4E37] mt-0.5">{{ $hist->note }}</p>
                            </div>
                            <div class="text-right shrink-0 text-[10px] text-[#6F4E37]">
                                <span class="block">{{ $hist->created_at ? $hist->created_at->format('d M Y, H:i') : '-' }} WIB</span>
                                <span>Oleh: {{ $hist->user ? $hist->user->name : 'Sistem' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-[#6F4E37]">Belum ada riwayat tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Pengubah Alur Status (1 Col) -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-5">
                <div>
                    <h3 class="text-base font-black text-[#3A2418]">Ubah Status Pesanan</h3>
                    <p class="text-xs text-[#6F4E37]">Perbarui alur tahapan persiapan pesanan pelanggan</p>
                </div>

                <div class="p-3 rounded-xl bg-[#FFF8EF] border border-[#D8B894]/40 text-xs text-[#3A2418] flex items-center justify-between">
                    <span>Status Saat Ini:</span>
                    <span class="font-black px-2.5 py-1 rounded-full bg-[#3A2418] text-white">{{ $order->status }}</span>
                </div>

                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Pilih Status Baru *</label>
                        <select name="status" class="w-full px-3 py-2.5 rounded-xl border border-[#D8B894] text-xs font-bold text-[#3A2418] focus:outline-none focus:border-[#B9824A]">
                            <option value="PENDING" {{ $order->status == 'PENDING' ? 'selected' : '' }}>PENDING (Menunggu Konfirmasi)</option>
                            <option value="CONFIRMED" {{ $order->status == 'CONFIRMED' ? 'selected' : '' }}>CONFIRMED (Dikonfirmasi)</option>
                            <option value="PREPARING" {{ $order->status == 'PREPARING' ? 'selected' : '' }}>PREPARING (Sedang Diracik)</option>
                            <option value="READY_FOR_PICKUP" {{ $order->status == 'READY_FOR_PICKUP' ? 'selected' : '' }}>READY_FOR_PICKUP (Siap Diambil)</option>
                            <option value="COMPLETED" {{ $order->status == 'COMPLETED' ? 'selected' : '' }}>COMPLETED (Selesai)</option>
                            <option value="CANCELLED" {{ $order->status == 'CANCELLED' ? 'selected' : '' }}>CANCELLED (Batalkan Pesanan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Catatan Internal Perubahan</label>
                        <textarea name="note" rows="3" placeholder="Contoh: Pembayaran telah diterima via QRIS kasir, pesanan diserahkan..." class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold shadow-md transition-all">
                        Perbarui Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
