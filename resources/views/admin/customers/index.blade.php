@extends('layouts.admin')

@section('title', 'Data Pelanggan — KopiZ Admin')
@section('header_title', 'Direktori Pelanggan KopiZ')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-black text-[#3A2418]">Data Pelanggan</h3>
            <p class="text-xs text-[#6F4E37]">Daftar pelanggan teragregasi otomatis dari riwayat transaksi checkout</p>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-3xl border border-[#D8B894]/60 shadow-sm max-w-md">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex gap-2">
            <div class="relative flex-1">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau nomor WhatsApp..." class="w-full pl-9 pr-3 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-[#3A2418] text-white text-xs font-bold">Cari</button>
            @if(request('q'))
                <a href="{{ route('admin.customers.index') }}" class="p-2 rounded-xl bg-[#F3E5D0] text-[#3A2418]">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFF8EF] border-b border-[#D8B894]/40 text-[#6F4E37] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Nama Pelanggan</th>
                        <th class="py-3 px-4">Nomor WhatsApp</th>
                        <th class="py-3 px-4">Username Instagram</th>
                        <th class="py-3 px-4">Total Frekuensi Order</th>
                        <th class="py-3 px-4">Total Nilai Belanja</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8B894]/30">
                    @forelse($customers as $c)
                        <tr class="hover:bg-[#FFF8EF]/40">
                            <td class="py-3 px-4 font-bold text-sm text-[#3A2418]">{{ $c->name }}</td>
                            <td class="py-3 px-4 font-mono text-[#6F4E37]">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $c->phone) }}" target="_blank" class="hover:underline text-[#2E7D5B] flex items-center gap-1">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                    <span>{{ $c->phone }}</span>
                                </a>
                            </td>
                            <td class="py-3 px-4 text-[#6F4E37]">{{ $c->instagram_handle ?: '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-[#F3E5D0] text-[#3A2418] font-bold text-xs">
                                    {{ $c->orders_count }} Pesanan
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-[#3A2418]">
                                Rp{{ number_format($c->orders_sum_total_amount ?: 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.orders.index', ['q' => $c->phone]) }}" class="px-3 py-1.5 rounded-xl bg-[#3A2418] text-white font-bold text-xs hover:bg-[#6F4E37]">
                                    Lihat Transaksi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-[#6F4E37]">Belum ada data pelanggan yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#D8B894]/30">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
