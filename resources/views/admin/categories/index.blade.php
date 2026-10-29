@extends('layouts.admin')

@section('title', 'Kategori Menu — KopiZ Admin')
@section('header_title', 'Manajemen Kategori Menu')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Form Tambah Kategori (1 Col) -->
    <div class="bg-white p-6 rounded-3xl border border-[#D8B894]/60 shadow-sm space-y-4">
        <h3 class="text-base font-black text-[#3A2418]">Tambah Kategori Baru</h3>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Nama Kategori *</label>
                <input type="text" name="name" required placeholder="Contoh: Signature Coffee" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Deskripsi Singkat</label>
                <input type="text" name="description" placeholder="Deskripsi kategori..." class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="1" min="0" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold shadow-md transition-all">
                Simpan Kategori
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Kategori (2 Cols) -->
    <div class="md:col-span-2 bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-[#D8B894]/40">
            <h3 class="text-lg font-black text-[#3A2418]">Daftar Kategori Menu</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFF8EF] border-b border-[#D8B894]/40 text-[#6F4E37] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Urutan</th>
                        <th class="py-3 px-4">Nama Kategori</th>
                        <th class="py-3 px-4">Slug</th>
                        <th class="py-3 px-4">Jumlah Produk</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8B894]/30">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-[#FFF8EF]/40">
                            <td class="py-3 px-4 font-bold text-[#3A2418]">{{ $cat->sort_order }}</td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-[#3A2418] block text-sm">{{ $cat->name }}</span>
                                <span class="text-[10px] text-[#6F4E37]">{{ $cat->description ?: '-' }}</span>
                            </td>
                            <td class="py-3 px-4 font-mono text-[#6F4E37]">{{ $cat->slug }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-[#F3E5D0] text-[#3A2418] font-bold text-[11px]">
                                    {{ $cat->products_count }} Menu
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-[#B93838]/15 text-[#B93838] hover:bg-[#B93838] hover:text-white" title="Hapus">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-[#6F4E37]">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
