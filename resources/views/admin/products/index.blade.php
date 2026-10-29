@extends('layouts.admin')

@section('title', 'Katalog Produk — KopiZ Admin')
@section('header_title', 'Manajemen Produk & Menu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black text-[#3A2418]">Daftar Menu Kopi</h3>
            <p class="text-xs text-[#6F4E37]">Kelola racikan, harga, status ketersediaan, dan stok produk</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Produk Baru</span>
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-3xl border border-[#D8B894]/60 shadow-sm">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <div class="sm:col-span-5 relative">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau SKU..." class="w-full pl-9 pr-3 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>

            <div class="sm:col-span-3">
                <select name="category_id" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
                    <option value="">Semua Status</option>
                    <option value="READY" {{ request('status') == 'READY' ? 'selected' : '' }}>READY</option>
                    <option value="PO" {{ request('status') == 'PO' ? 'selected' : '' }}>PO</option>
                    <option value="SOLD_OUT" {{ request('status') == 'SOLD_OUT' ? 'selected' : '' }}>SOLD_OUT</option>
                    <option value="INACTIVE" {{ request('status') == 'INACTIVE' ? 'selected' : '' }}>INACTIVE</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2 rounded-xl bg-[#3A2418] text-white text-xs font-bold">Filter</button>
                <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl bg-[#F3E5D0] text-[#3A2418]" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFF8EF] border-b border-[#D8B894]/40 text-[#6F4E37] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Menu</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Harga Satuan</th>
                        <th class="py-3 px-4">Stok</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Unggulan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8B894]/30">
                    @forelse($products as $p)
                        <tr class="hover:bg-[#FFF8EF]/40">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $p->image_path ?: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&auto=format&fit=crop&q=80' }}" alt="{{ $p->name }}" class="w-10 h-10 rounded-xl object-cover bg-[#F3E5D0] shrink-0">
                                    <div>
                                        <span class="font-bold text-[#3A2418] block text-sm">{{ $p->name }}</span>
                                        <span class="text-[10px] text-[#6F4E37] font-mono">SKU: {{ $p->sku ?: '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-[#6F4E37] font-medium">
                                {{ $p->category ? $p->category->name : '-' }}
                            </td>
                            <td class="py-3 px-4 font-bold text-[#3A2418]">
                                Rp{{ number_format($p->price, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-[#6F4E37]">
                                @if($p->track_inventory)
                                    <span class="font-bold {{ $p->stock_quantity <= 5 ? 'text-[#B93838]' : 'text-[#2E7D5B]' }}">
                                        {{ $p->stock_quantity }} unit
                                    </span>
                                @else
                                    <span class="text-gray-400">Tak dilacak</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $st = match($p->status) {
                                        'READY' => 'bg-[#2E7D5B]/15 text-[#2E7D5B]',
                                        'PO' => 'bg-[#B9824A]/15 text-[#B9824A]',
                                        'SOLD_OUT' => 'bg-[#B93838]/15 text-[#B93838]',
                                        default => 'bg-gray-100 text-gray-600'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $st }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($p->is_featured)
                                    <span class="text-[#B9824A] font-bold text-xs">★ Ya</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.products.toggle-status', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg border border-[#D8B894] hover:bg-[#F3E5D0] text-[10px] font-bold text-[#3A2418]" title="Ubah READY / SOLD_OUT">
                                            Toggle Stok
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.products.edit', $p->id) }}" class="p-1.5 rounded-lg bg-[#3A2418] text-white hover:bg-[#6F4E37]" title="Edit">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan produk ini? Riwayat transaksi lama tetap aman.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-[#B93838]/15 text-[#B93838] hover:bg-[#B93838] hover:text-white" title="Arsipkan">
                                            <i data-lucide="archive" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-[#6F4E37]">Tidak ada produk ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#D8B894]/30">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
