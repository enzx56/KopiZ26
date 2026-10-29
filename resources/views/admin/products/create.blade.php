@extends('layouts.admin')

@section('title', 'Tambah Produk — KopiZ Admin')
@section('header_title', 'Tambah Menu Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-[#6F4E37] hover:text-[#3A2418] flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Menu</span>
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#D8B894]/60 shadow-sm">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Nama Produk *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Caramel Macchiato" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Kategori</label>
                    <select name="category_id" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Harga (Rupiah) *</label>
                    <input type="number" name="price" value="{{ old('price', 15000) }}" required min="0" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">SKU (Opsional)</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="KPZ-SIG-..." class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Status Produk *</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
                        <option value="READY" selected>READY</option>
                        <option value="PO">PO</option>
                        <option value="SOLD_OUT">SOLD_OUT</option>
                        <option value="INACTIVE">INACTIVE</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Deskripsi Singkat</label>
                <input type="text" name="short_description" value="{{ old('short_description') }}" placeholder="Contoh: Kopi creamy rasa legit..." class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Deskripsi Lengkap / Racikan</label>
                <textarea name="description" rows="3" placeholder="Informasi komposisi atau aroma kopi..." class="w-full px-4 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">URL Foto (Unsplash / CDN)</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Atau Unggah File Foto</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none bg-[#FFF8EF]">
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/40 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <label class="flex items-center gap-2 text-xs font-bold text-[#3A2418] cursor-pointer">
                    <input type="checkbox" name="track_inventory" value="1" {{ old('track_inventory') ? 'checked' : '' }} class="rounded text-[#3A2418]">
                    <span>Lacak Stok</span>
                </label>
                <div>
                    <label class="block text-[11px] font-bold text-[#6F4E37] mb-1">Jumlah Stok Awal</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 50) }}" min="0" class="w-full px-3 py-1.5 rounded-lg border border-[#D8B894] text-xs">
                </div>
                <label class="flex items-center gap-2 text-xs font-bold text-[#3A2418] cursor-pointer sm:pt-4">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded text-[#3A2418]">
                    <span>Menu Unggulan</span>
                </label>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-[#D8B894] text-xs font-bold text-[#3A2418]">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold shadow-md">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection
