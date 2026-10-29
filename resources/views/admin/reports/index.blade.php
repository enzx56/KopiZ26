@extends('layouts.admin')

@section('title', 'Laporan & Analitik — KopiZ Admin')
@section('header_title', 'Laporan Penjualan & Ekspor CSV')

@section('content')
<div class="space-y-6">
    <!-- Filter & Export Controls -->
    <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Status Order</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
                    <option value="">Semua Status</option>
                    <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>COMPLETED (Selesai)</option>
                    <option value="CONFIRMED" {{ request('status') == 'CONFIRMED' ? 'selected' : '' }}>CONFIRMED</option>
                    <option value="PREPARING" {{ request('status') == 'PREPARING' ? 'selected' : '' }}>PREPARING</option>
                    <option value="READY_FOR_PICKUP" {{ request('status') == 'READY_FOR_PICKUP' ? 'selected' : '' }}>READY_FOR_PICKUP</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                    <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>CANCELLED</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Jenis Order</label>
                <select name="order_type" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
                    <option value="">Semua Tipe</option>
                    <option value="REGULAR" {{ request('order_type') == 'REGULAR' ? 'selected' : '' }}>REGULAR</option>
                    <option value="PRE_ORDER" {{ request('order_type') == 'PRE_ORDER' ? 'selected' : '' }}>PRE_ORDER</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2.5 rounded-xl bg-[#3A2418] text-white text-xs font-bold">Filter</button>
                <a href="{{ route('admin.reports.index') }}" class="p-2.5 rounded-xl bg-[#F3E5D0] text-[#3A2418]" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            </div>
        </form>

        <!-- CSV Export Button with same query parameters (PRD Section 18) -->
        <div class="pt-4 border-t border-[#D8B894]/40 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-[#6F4E37]">Filter di atas otomatis diterapkan pada data tampilan dan file ekspor CSV.</span>
            @php
                $exportQuery = request()->query();
            @endphp
            <a href="{{ route('admin.reports.export-csv', $exportQuery) }}" class="px-5 py-2.5 rounded-2xl bg-[#2E7D5B] hover:bg-[#2E7D5B]/90 text-white text-xs font-bold flex items-center gap-2 shadow-md">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Ekspor CSV (Excel UTF-8 BOM)</span>
            </a>
        </div>
    </div>

    <!-- Metrik Definisi Laporan (PRD Bagian 18) -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-3xl border border-[#D8B894]/60 shadow-sm">
            <span class="text-xs font-bold text-[#6F4E37] block">Total Order Sesuai Filter</span>
            <span class="text-2xl font-black text-[#3A2418] block mt-1">{{ $totalOrdersCount }} Transaksi</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-[#D8B894]/60 shadow-sm">
            <span class="text-xs font-bold text-[#2E7D5B] block">Penjualan Selesai (COMPLETED)</span>
            <span class="text-2xl font-black text-[#2E7D5B] block mt-1">Rp{{ number_format($completedRevenue, 0, ',', '.') }}</span>
            <span class="text-[10px] text-[#6F4E37] block mt-0.5">{{ $completedCount }} pesanan selesai</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-[#D8B894]/60 shadow-sm">
            <span class="text-xs font-bold text-[#B9824A] block">Nilai Order Non-Batal (Gross)</span>
            <span class="text-2xl font-black text-[#B9824A] block mt-1">Rp{{ number_format($grossOrderValue, 0, ',', '.') }}</span>
            <span class="text-[10px] text-[#6F4E37] block mt-0.5">Termasuk pending/proses</span>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-[#D8B894]/60 shadow-sm">
            <span class="text-xs font-bold text-[#B93838] block">Order Dibatalkan (Terpisah)</span>
            <span class="text-2xl font-black text-[#B93838] block mt-1">{{ $cancelledOrdersCount }} Order</span>
            <span class="text-[10px] text-[#6F4E37] block mt-0.5">Tidak dihitung penjualan</span>
        </div>
    </div>

    <!-- Tabel Detail Laporan -->
    <div class="bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#D8B894]/40 flex justify-between items-center">
            <h3 class="text-base font-black text-[#3A2418]">Rincian Transaksi Terfilter</h3>
            <span class="text-xs text-[#6F4E37]">{{ $filteredOrders->total() }} baris data ditemukan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFF8EF] border-b border-[#D8B894]/40 text-[#6F4E37] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Kode Order</th>
                        <th class="py-3 px-4">Tanggal Order</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Menu & Kuantitas</th>
                        <th class="py-3 px-4">Total Nilai</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8B894]/30">
                    @forelse($filteredOrders as $ord)
                        <tr class="hover:bg-[#FFF8EF]/40">
                            <td class="py-3 px-4 font-mono font-bold text-[#3A2418]">{{ $ord->order_code }}</td>
                            <td class="py-3 px-4 text-[#6F4E37]">{{ $ord->order_date ? $ord->order_date->format('Y-m-d H:i') : '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-[#3A2418] block">{{ $ord->customer->name }}</span>
                                <span class="text-[#6F4E37] text-[10px]">{{ $ord->customer->phone }}</span>
                            </td>
                            <td class="py-3 px-4 text-[#6F4E37]">
                                @foreach($ord->items as $it)
                                    <div>{{ $it->product_name_snapshot }} ({{ $it->quantity }}x)</div>
                                @endforeach
                            </td>
                            <td class="py-3 px-4 font-black text-[#3A2418]">
                                Rp{{ number_format($ord->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ord->status === 'COMPLETED' ? 'bg-[#3A2418] text-white' : ($ord->status === 'CANCELLED' ? 'bg-[#B93838]/15 text-[#B93838]' : 'bg-[#F3E5D0] text-[#3A2418]') }}">
                                    {{ $ord->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-[#6F4E37]">Tidak ada data untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#D8B894]/30">
            {{ $filteredOrders->links() }}
        </div>
    </div>
</div>
@endsection
