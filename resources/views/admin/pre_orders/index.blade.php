@extends('layouts.admin')

@section('title', 'Pre-Order — KopiZ Admin')
@section('header_title', 'Manajemen Program Pre-Order (PO)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black text-[#3A2418]">Batch Pre-Order KopiZ</h3>
            <p class="text-xs text-[#6F4E37]">Kelola kuota unit, jadwal rilis & penutupan, serta status batch</p>
        </div>
        <a href="{{ route('admin.pre-orders.create') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Buka Batch PO Baru</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFF8EF] border-b border-[#D8B894]/40 text-[#6F4E37] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Nama Batch</th>
                        <th class="py-3 px-4">Jadwal PO & Pickup</th>
                        <th class="py-3 px-4">Kuota Unit</th>
                        <th class="py-3 px-4">Pesanan Masuk</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8B894]/30">
                    @forelse($preOrders as $po)
                        <tr class="hover:bg-[#FFF8EF]/40">
                            <td class="py-3 px-4">
                                <span class="font-bold text-[#3A2418] block text-sm">{{ $po->name }}</span>
                                <span class="text-[10px] text-[#6F4E37]">{{ $po->products->pluck('name')->join(', ') }}</span>
                            </td>
                            <td class="py-3 px-4 text-[11px] text-[#6F4E37] space-y-0.5">
                                <div>Tutup: <strong class="text-[#3A2418]">{{ $po->ends_at ? $po->ends_at->format('d M Y, H:i') : '-' }}</strong></div>
                                <div>Pickup: <strong class="text-[#3A2418]">{{ $po->pickup_at ? $po->pickup_at->format('d M Y, H:i') : '-' }}</strong></div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-[#3A2418]">{{ $po->reserved_units }} / {{ $po->quota_units }} Unit</div>
                                <span class="text-[10px] text-[#2E7D5B] font-semibold">Sisa {{ $po->remainingQuota() }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-[#F3E5D0] text-[#3A2418] font-bold text-[11px]">
                                    {{ $po->orders_count }} Transaksi
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $poBadge = match($po->status) {
                                        'OPEN' => 'bg-[#2E7D5B]/15 text-[#2E7D5B]',
                                        'SCHEDULED' => 'bg-[#B7791F]/15 text-[#B7791F]',
                                        'SOLD_OUT' => 'bg-[#B93838]/15 text-[#B93838]',
                                        'COMPLETED' => 'bg-[#3A2418] text-white',
                                        default => 'bg-gray-100 text-gray-600'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $poBadge }}">
                                    {{ $po->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('po.detail', $po->slug) }}" target="_blank" class="p-1.5 rounded-lg bg-[#F3E5D0] text-[#3A2418] hover:bg-[#D8B894]" title="Lihat di Web">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <a href="{{ route('admin.pre-orders.edit', $po->id) }}" class="p-1.5 rounded-lg bg-[#3A2418] text-white hover:bg-[#6F4E37]" title="Edit">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <form action="{{ route('admin.pre-orders.destroy', $po->id) }}" method="POST" onsubmit="return confirm('Hapus Pre-Order ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-[#B93838]/15 text-[#B93838] hover:bg-[#B93838] hover:text-white" title="Hapus">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-[#6F4E37]">Belum ada batch Pre-Order yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#D8B894]/30">
            {{ $preOrders->links() }}
        </div>
    </div>
</div>
@endsection
