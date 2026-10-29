@extends('layouts.admin')

@section('title', 'Buka Pre-Order — KopiZ Admin')
@section('header_title', 'Buka Batch Pre-Order Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.pre-orders.index') }}" class="text-xs font-bold text-[#6F4E37] hover:text-[#3A2418] flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Pre-Order</span>
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#D8B894]/60 shadow-sm">
        <form action="{{ route('admin.pre-orders.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Nama Batch Pre-Order *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Batch Literan Spesial Liburan" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Deskripsi & Syarat Pre-Order</label>
                <textarea name="description" rows="3" placeholder="Informasi kuota, keunggulan batch, atau instruksi..." class="w-full px-4 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">URL Banner Pre-Order (Opsional)</label>
                <input type="url" name="banner_url" value="{{ old('banner_url') }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Waktu Buka PO</label>
                    <input type="datetime-local" name="starts_at" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Waktu Tutup PO *</label>
                    <input type="datetime-local" name="ends_at" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Jadwal Pengambilan (Pickup) *</label>
                    <input type="datetime-local" name="pickup_at" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Total Kuota (Unit Produk) *</label>
                    <input type="number" name="quota_units" value="{{ old('quota_units', 30) }}" required min="1" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Status Awal *</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                        <option value="OPEN" selected>OPEN (Langsung Terima Pesanan)</option>
                        <option value="SCHEDULED">SCHEDULED (Terjadwal)</option>
                        <option value="DRAFT">DRAFT (Disimpan Dulu)</option>
                    </select>
                </div>
            </div>

            <!-- Pilih Produk & Harga Khusus PO -->
            <div class="pt-4 border-t border-[#D8B894]/40 space-y-3">
                <label class="block text-xs font-bold text-[#3A2418]">Pilih Menu yang Termasuk dalam Batch Ini *</label>
                <div class="space-y-3 bg-[#FFF8EF] p-4 rounded-2xl border border-[#D8B894]/40 max-h-60 overflow-y-auto">
                    @foreach($products as $i => $prod)
                        <div class="flex items-center justify-between gap-4 p-2 bg-white rounded-xl border border-[#D8B894]/30">
                            <label class="flex items-center gap-2 text-xs font-bold text-[#3A2418] cursor-pointer">
                                <input type="checkbox" name="products[{{ $i }}][product_id]" value="{{ $prod->id }}" {{ $i < 2 ? 'checked' : '' }} class="rounded text-[#3A2418]">
                                <span>{{ $prod->name }}</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] text-[#6F4E37]">Harga PO: Rp</span>
                                <input type="number" name="products[{{ $i }}][price]" value="{{ $prod->price }}" min="0" class="w-24 px-2 py-1 rounded-lg border border-[#D8B894] text-xs font-bold text-[#3A2418]">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Instruksi Pengambilan Tambahan</label>
                <input type="text" name="instruction_notes" placeholder="Contoh: Pengambilan dilakukan di kedai KopiZ mulai pukul 14:00 - 18:00 WIB..." class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('admin.pre-orders.index') }}" class="px-5 py-2.5 rounded-xl border border-[#D8B894] text-xs font-bold text-[#3A2418]">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold shadow-md">Simpan Batch Pre-Order</button>
            </div>
        </form>
    </div>
</div>
@endsection
