@extends('layouts.admin')

@section('title', 'Manajemen Pesanan — KopiZ Admin')
@section('header_title', 'Daftar Pesanan & Order Transaksional')

@section('content')
<div class="space-y-6">
    <!-- Filter & Search -->
    <div class="bg-white p-5 rounded-3xl border border-[#D8B894]/60 shadow-sm">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <div class="sm:col-span-4 relative">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Kode Order, Nama, atau No HP..." class="w-full pl-9 pr-3 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>

            <div class="sm:col-span-2">
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
                    <option value="">Semua Status</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                    <option value="CONFIRMED" {{ request('status') == 'CONFIRMED' ? 'selected' : '' }}>CONFIRMED</option>
                    <option value="PREPARING" {{ request('status') == 'PREPARING' ? 'selected' : '' }}>PREPARING</option>
                    <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>COMPLETED</option>
                    <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>CANCELLED</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="order_type" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
                    <option value="">Semua Tipe</option>
                    <option value="REGULAR" {{ request('order_type') == 'REGULAR' ? 'selected' : '' }}>REGULAR</option>
                    <option value="PRE_ORDER" {{ request('order_type') == 'PRE_ORDER' ? 'selected' : '' }}>PRE_ORDER</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2 rounded-xl bg-[#3A2418] text-white text-xs font-bold">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="p-2 rounded-xl bg-[#F3E5D0] text-[#3A2418]" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Pesanan -->
    <div class="bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFF8EF] border-b border-[#D8B894]/40 text-[#6F4E37] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Kode Order</th>
                        <th class="py-3 px-4">Tanggal Order</th>
                        <th class="py-3 px-4">Pemesan</th>
                        <th class="py-3 px-4">Tipe & Rincian Item</th>
                        <th class="py-3 px-4">Total</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8B894]/30">
                    @forelse($orders as $order)
                        <tr class="hover:bg-[#FFF8EF]/40">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono font-bold text-sm text-[#3A2418] hover:underline">
                                    {{ $order->order_code }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-[#6F4E37] text-[11px]">
                                {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : '-' }} WIB
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-[#3A2418] block">{{ $order->customer->name }}</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer->phone) }}" target="_blank" class="text-[#2E7D5B] font-semibold text-[10px] hover:underline flex items-center gap-1">
                                    <i data-lucide="phone" class="w-3 h-3"></i>
                                    <span>{{ $order->customer->phone }}</span>
                                </a>
                            </td>
                            <td class="py-3 px-4 max-w-xs">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $order->order_type === 'PRE_ORDER' ? 'bg-[#B9824A]/20 text-[#B9824A]' : 'bg-[#F3E5D0] text-[#3A2418]' }} mb-1 inline-block">
                                    {{ $order->order_type }}
                                </span>
                                <div class="text-[11px] text-[#6F4E37] truncate">
                                    @foreach($order->items as $it)
                                        {{ $it->product_name_snapshot }} ({{ $it->quantity }}x){{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3 px-4 font-black text-sm text-[#3A2418]">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $pill = match($order->status) {
                                        'PENDING' => 'bg-[#B7791F]/15 text-[#B7791F]',
                                        'CONFIRMED' => 'bg-[#2E7D5B]/15 text-[#2E7D5B]',
                                        'PREPARING' => 'bg-[#B9824A]/15 text-[#B9824A]',
                                        'COMPLETED' => 'bg-[#3A2418] text-white',
                                        'CANCELLED' => 'bg-[#B93838]/15 text-[#B93838]',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $pill }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @php
                                        $cPhone = preg_replace('/[^0-9]/', '', $order->customer->phone ?? '');
                                        if (str_starts_with($cPhone, '0')) {
                                            $cPhone = '62' . substr($cPhone, 1);
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $cPhone }}?text={{ urlencode('Halo Kak ' . $order->customer->name . ', kami dari KopiZ mengenai pesanan #' . $order->order_code . '...') }}" target="_blank" class="p-1.5 rounded-lg bg-[#2E7D5B]/15 text-[#2E7D5B] hover:bg-[#2E7D5B]/25 transition-all" title="Chat WhatsApp Pelanggan">
                                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <a href="{{ route('order.track', $order->order_code) }}" target="_blank" class="p-1.5 rounded-lg bg-[#F3E5D0] text-[#3A2418] hover:bg-[#D8B894]" title="Buka Halaman Publik Pelanggan">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 rounded-xl bg-[#3A2418] text-white font-bold text-xs hover:bg-[#6F4E37] transition-all">
                                        Kelola
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-[#6F4E37]">Tidak ada data pesanan yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#D8B894]/30">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
