@extends('layouts.admin')

@section('title', 'Dashboard Operasional — KopiZ')
@section('header_title', 'Ringkasan Operasional & Bisnis')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards Grid (Data Nyata Database - PRD Bagian 13) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Order Hari Ini -->
        <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#6F4E37] uppercase tracking-wider block">Order Hari Ini</span>
                <span class="text-3xl font-black text-[#3A2418] block mt-1">{{ $todayOrdersCount }}</span>
                <span class="text-[11px] text-[#2E7D5B] font-semibold block mt-1">Transaksional Realtime</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F3E5D0] text-[#3A2418] flex items-center justify-center">
                <i data-lucide="shopping-bag" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Pending Confirmation -->
        <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#B7791F] uppercase tracking-wider block">Menunggu Konfirmasi</span>
                <span class="text-3xl font-black text-[#B7791F] block mt-1">{{ $pendingOrdersCount }}</span>
                <a href="{{ route('admin.orders.index', ['status' => 'PENDING']) }}" class="text-[11px] font-bold text-[#B7791F] underline block mt-1">Lihat Pesanan &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#B7791F]/15 text-[#B7791F] flex items-center justify-center">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Sedang Diracik / Proses -->
        <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Sedang Diproses</span>
                <span class="text-3xl font-black text-[#B9824A] block mt-1">{{ $processingOrdersCount }}</span>
                <span class="text-[11px] text-[#6F4E37] font-semibold block mt-1">Confirmed / Preparing</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#B9824A]/15 text-[#B9824A] flex items-center justify-center">
                <i data-lucide="coffee" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Total Omset Selesai -->
        <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#2E7D5B] uppercase tracking-wider block">Penjualan Selesai</span>
                <span class="text-2xl font-black text-[#2E7D5B] block mt-1">Rp{{ number_format($completedRevenue, 0, ',', '.') }}</span>
                <span class="text-[11px] text-[#6F4E37] block mt-1">{{ $completedOrdersCount }} Order Selesai</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#2E7D5B]/15 text-[#2E7D5B] flex items-center justify-center">
                <i data-lucide="badge-dollar-sign" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Active PO & Product Badges -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-6 rounded-3xl bg-white border border-[#D8B894]/60 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#3A2418] text-white flex items-center justify-center">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-black text-[#3A2418] text-lg">{{ $activePreOrdersCount }} Batch Pre-Order Aktif</h4>
                    <p class="text-xs text-[#6F4E37]">Menerima pemesanan dengan kuota terbatas</p>
                </div>
            </div>
            <a href="{{ route('admin.pre-orders.index') }}" class="px-4 py-2 rounded-xl bg-[#F3E5D0] hover:bg-[#D8B894] text-xs font-bold text-[#3A2418]">Kelola PO</a>
        </div>

        <div class="p-6 rounded-3xl bg-white border border-[#D8B894]/60 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#B9824A] text-white flex items-center justify-center">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-black text-[#3A2418] text-lg">{{ $activeProductsCount }} Menu Kopi Aktif</h4>
                    <p class="text-xs text-[#6F4E37]">Tampil di katalog pemesanan pelanggan</p>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-xl bg-[#F3E5D0] hover:bg-[#D8B894] text-xs font-bold text-[#3A2418]">Kelola Produk</a>
        </div>
    </div>

    <!-- Pesanan Terbaru & Produk Terlaris -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-[#3A2418]">Pesanan Terbaru Masuk</h3>
                    <p class="text-xs text-[#6F4E37]">Daftar transaksi yang perlu dipantau & diproses</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#B9824A] hover:underline flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-[#D8B894]/40 text-[#6F4E37] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="pb-3">Kode Order</th>
                            <th class="pb-3">Pelanggan</th>
                            <th class="pb-3">Tipe</th>
                            <th class="pb-3">Total</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#D8B894]/30">
                        @forelse($recentOrders as $o)
                            <tr class="hover:bg-[#FFF8EF]/50">
                                <td class="py-3 font-mono font-bold text-[#3A2418]">
                                    <a href="{{ route('admin.orders.show', $o->id) }}" class="hover:underline">{{ $o->order_code }}</a>
                                </td>
                                <td class="py-3">
                                    <span class="font-bold block text-[#3A2418]">{{ $o->customer->name }}</span>
                                    <span class="text-[#6F4E37] text-[10px]">{{ $o->customer->phone }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $o->order_type === 'PRE_ORDER' ? 'bg-[#B9824A]/20 text-[#B9824A]' : 'bg-[#F3E5D0] text-[#3A2418]' }}">
                                        {{ $o->order_type }}
                                    </span>
                                </td>
                                <td class="py-3 font-bold text-[#3A2418]">
                                    Rp{{ number_format($o->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3">
                                    @php
                                        $pill = match($o->status) {
                                            'PENDING' => 'bg-[#B7791F]/15 text-[#B7791F]',
                                            'CONFIRMED' => 'bg-[#2E7D5B]/15 text-[#2E7D5B]',
                                            'PREPARING' => 'bg-[#B9824A]/15 text-[#B9824A]',
                                            'READY_FOR_PICKUP' => 'bg-[#2E7D5B]/20 text-[#2E7D5B]',
                                            'COMPLETED' => 'bg-[#3A2418] text-white',
                                            'CANCELLED' => 'bg-[#B93838]/15 text-[#B93838]',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pill }}">
                                        {{ $o->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.orders.show', $o->id) }}" class="px-2.5 py-1 rounded-lg bg-[#3A2418] text-white font-bold text-[11px] hover:bg-[#6F4E37]">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-[#6F4E37]">Belum ada pesanan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Products (1 Col) -->
        <div class="bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm p-6 sm:p-8 space-y-6">
            <div>
                <h3 class="text-lg font-black text-[#3A2418]">Menu Terlaris</h3>
                <p class="text-xs text-[#6F4E37]">Kuantitas unit terjual terbanyak</p>
            </div>

            <div class="space-y-4">
                @forelse($topProducts as $item)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/40">
                        <div>
                            <span class="font-bold text-xs text-[#3A2418] block">{{ $item->product_name_snapshot }}</span>
                            <span class="text-[10px] text-[#6F4E37]">Omset: Rp{{ number_format($item->total_sales, 0, ',', '.') }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl bg-[#3A2418] text-white font-black text-xs">
                            {{ $item->total_qty }} Unit
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-[#6F4E37] text-center py-6">Belum ada data penjualan tercatat.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
