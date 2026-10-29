@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_code . ' — KopiZ Admin')
@section('header_title', 'Detail Pesanan: ' . $order->order_code)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Top Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#6F4E37] hover:text-[#3A2418] flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Pesanan</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="px-4 py-2 rounded-xl bg-[#F3E5D0] hover:bg-[#D8B894] text-[#3A2418] text-xs font-bold flex items-center gap-1.5 transition-all">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak Ringkasan</span>
            </a>
            <a href="{{ route('order.track', $order->order_code) }}" target="_blank" class="px-4 py-2 rounded-xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold flex items-center gap-1.5 transition-all">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Lihat Status Publik</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi WhatsApp Setelah Update Status -->
    @if(session('just_updated_status') && isset($waTemplates[session('just_updated_status')]))
        <div class="p-4 rounded-2xl bg-[#E8F5E9] border border-[#2E7D5B]/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#2E7D5B] text-white flex items-center justify-center shrink-0">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black text-[#2E7D5B]">Kirim Notifikasi via WhatsApp ke Pelanggan?</h4>
                    <p class="text-[11px] text-[#2E7D5B]/80">Status baru: <strong>{{ session('just_updated_status') }}</strong>. Kirim pesan ke WhatsApp pelanggan sekarang.</p>
                </div>
            </div>
            <a href="https://wa.me/{{ $customerPhone }}?text={{ urlencode($waTemplates[session('just_updated_status')]) }}" target="_blank" class="px-4 py-2 rounded-xl bg-[#2E7D5B] hover:bg-[#256349] text-white text-xs font-bold flex items-center gap-2 shrink-0 shadow-sm transition-all">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>Kirim WhatsApp Sekarang</span>
            </a>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Rincian Order Items & Data Pelanggan (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Pelanggan & Info Transaksi -->
            <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-black text-[#3A2418]">Informasi Pemesan</h3>
                    @php
                        $badgeClasses = match($order->status) {
                            'PENDING' => 'bg-[#B7791F]/15 text-[#B7791F] border-[#B7791F]/30',
                            'CONFIRMED' => 'bg-[#2E7D5B]/15 text-[#2E7D5B] border-[#2E7D5B]/30',
                            'PREPARING' => 'bg-[#B9824A]/15 text-[#B9824A] border-[#B9824A]/30',
                            'COMPLETED' => 'bg-[#3A2418] text-white border-[#3A2418]',
                            'CANCELLED' => 'bg-[#B93838]/15 text-[#B93838] border-[#B93838]/30',
                            default => 'bg-gray-100 text-gray-700 border-gray-300'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-black border {{ $badgeClasses }}">
                        {{ $order->status }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-[#6F4E37] block">Nama Pemesan:</span>
                        <span class="font-bold text-sm text-[#3A2418]">{{ $order->customer->name }}</span>
                    </div>
                    <div>
                        <span class="text-[#6F4E37] block">Nomor WhatsApp:</span>
                        <a href="https://wa.me/{{ $customerPhone }}" target="_blank" class="font-bold text-sm text-[#2E7D5B] hover:underline flex items-center gap-1">
                            <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                            <span>{{ $order->customer->phone }}</span>
                        </a>
                    </div>
                    <div>
                        <span class="text-[#6F4E37] block">Jenis Pesanan:</span>
                        <span class="font-bold text-sm text-[#B9824A]">{{ $order->order_type }}</span>
                        @if($order->preOrder)
                            <span class="block text-[11px] text-[#6F4E37]">Batch: {{ $order->preOrder->name }}</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-[#6F4E37] block">Tanggal Dibuat:</span>
                        <span class="font-bold text-sm text-[#3A2418]">{{ $order->order_date ? $order->order_date->format('d M Y, H:i') : '-' }} WIB</span>
                    </div>
                </div>

                @if($order->customer_notes)
                    <div class="p-3.5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/40 text-xs">
                        <span class="font-bold text-[#3A2418] block mb-0.5">Catatan Pemesan:</span>
                        <p class="text-[#6F4E37]">{{ $order->customer_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Rincian Item -->
            <div class="bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#D8B894]/40">
                    <h3 class="text-base font-black text-[#3A2418]">Item Pesanan</h3>
                    <p class="text-[11px] text-[#6F4E37]">Menu pesanan pelanggan via WhatsApp</p>
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
                                <td colspan="3" class="py-4 px-4 text-right">TOTAL PESANAN:</td>
                                <td class="py-4 px-4 text-right text-base text-[#2E7D5B]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Riwayat Perubahan Status (Audit Trail) -->
            <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4">
                <h3 class="text-base font-black text-[#3A2418]">Riwayat Status Pesanan</h3>
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

        <!-- Kolom Kanan: Verifikasi & Aksi WhatsApp (1 Col) -->
        <div class="space-y-6">
            <!-- Kartu Aksi Cepat Verifikasi Status -->
            <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4">
                <div>
                    <h3 class="text-base font-black text-[#3A2418]">Alur Pesanan WhatsApp</h3>
                    <p class="text-xs text-[#6F4E37]">1-Klik untuk memproses status pesanan</p>
                </div>

                <div class="space-y-2">
                    @if($order->status === 'PENDING')
                        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="CONFIRMED">
                            <input type="hidden" name="note" value="Pesanan dikonfirmasi setelah chat WA masuk dari pelanggan.">
                            <button type="submit" class="w-full py-3 rounded-2xl bg-[#2E7D5B] hover:bg-[#256349] text-white text-xs font-black shadow-md flex items-center justify-center gap-2 transition-all">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span>Konfirmasi Pesanan WA</span>
                            </button>
                        </form>
                    @elseif($order->status === 'CONFIRMED')
                        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="PREPARING">
                            <input type="hidden" name="note" value="Pesanan mulai diracik oleh Barista.">
                            <button type="submit" class="w-full py-3 rounded-2xl bg-[#B9824A] hover:bg-[#976834] text-white text-xs font-black shadow-md flex items-center justify-center gap-2 transition-all">
                                <i data-lucide="coffee" class="w-4 h-4"></i>
                                <span>Mulai Diracik Barista</span>
                            </button>
                        </form>
                    @elseif($order->status === 'PREPARING')
                        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="COMPLETED">
                            <input type="hidden" name="note" value="Pesanan telah selesai diproses dan diserahkan.">
                            <button type="submit" class="w-full py-3 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-black shadow-md flex items-center justify-center gap-2 transition-all">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>Tandai Pesanan Selesai</span>
                            </button>
                        </form>
                    @elseif($order->status === 'COMPLETED')
                        <div class="p-3 rounded-2xl bg-[#2E7D5B]/10 border border-[#2E7D5B]/30 text-center">
                            <span class="text-xs font-black text-[#2E7D5B] block">Pesanan Telah Selesai ✓</span>
                            <span class="text-[11px] text-[#6F4E37]">Transaksi selesai via WhatsApp.</span>
                        </div>
                    @endif
                </div>

                <hr class="border-[#D8B894]/40 my-3">

                <!-- Hubungi WhatsApp Pelanggan -->
                <div class="space-y-2">
                    <h4 class="text-xs font-black text-[#3A2418] flex items-center gap-1.5">
                        <i data-lucide="message-square" class="w-4 h-4 text-[#2E7D5B]"></i>
                        <span>Aksi WhatsApp</span>
                    </h4>

                    @if(isset($waTemplates[$order->status]))
                        <a href="https://wa.me/{{ $customerPhone }}?text={{ urlencode($waTemplates[$order->status]) }}" target="_blank" class="w-full py-2.5 px-3 rounded-xl bg-[#2E7D5B]/15 hover:bg-[#2E7D5B]/25 text-[#2E7D5B] text-xs font-bold flex items-center justify-between transition-all">
                            <span class="truncate">Kirim Notif Status ({{ $order->status }})</span>
                            <i data-lucide="send" class="w-3.5 h-3.5 shrink-0"></i>
                        </a>
                    @endif

                    <a href="https://wa.me/{{ $customerPhone }}?text={{ urlencode('Halo Kak ' . $order->customer->name . ', kami dari KopiZ terkait pesanan #' . $order->order_code . '...') }}" target="_blank" class="w-full py-2.5 px-3 rounded-xl bg-[#2E7D5B] hover:bg-[#256349] text-white text-xs font-bold flex items-center justify-between transition-all shadow-sm">
                        <span>Chat Pelanggan di WhatsApp</span>
                        <i data-lucide="external-link" class="w-3.5 h-3.5 shrink-0"></i>
                    </a>
                </div>
            </div>

            <!-- Form Manual Pengubah Status & Pembatalan -->
            <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4">
                <h3 class="text-sm font-black text-[#3A2418]">Ubah Status Manual</h3>

                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Pilih Status Baru *</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs font-bold text-[#3A2418] focus:outline-none focus:border-[#B9824A]">
                            <option value="PENDING" {{ $order->status == 'PENDING' ? 'selected' : '' }}>PENDING (Menunggu Konfirmasi WA)</option>
                            <option value="CONFIRMED" {{ $order->status == 'CONFIRMED' ? 'selected' : '' }}>CONFIRMED (Dikonfirmasi)</option>
                            <option value="PREPARING" {{ $order->status == 'PREPARING' ? 'selected' : '' }}>PREPARING (Sedang Diracik)</option>
                            <option value="COMPLETED" {{ $order->status == 'COMPLETED' ? 'selected' : '' }}>COMPLETED (Selesai)</option>
                            <option value="CANCELLED" {{ $order->status == 'CANCELLED' ? 'selected' : '' }}>CANCELLED (Batalkan Pesanan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Catatan</label>
                        <textarea name="note" rows="2" placeholder="Catatan internal..." class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]"></textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold shadow-sm transition-all">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
