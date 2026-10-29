@extends('layouts.admin')

@section('title', 'Edit Pre-Order — KopiZ Admin')
@section('header_title', 'Edit Batch: ' . $preOrder->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.pre-orders.index') }}" class="text-xs font-bold text-[#6F4E37] hover:text-[#3A2418] flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Pre-Order</span>
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#D8B894]/60 shadow-sm">
        <form action="{{ route('admin.pre-orders.update', $preOrder->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Nama Batch Pre-Order *</label>
                <input type="text" name="name" value="{{ old('name', $preOrder->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Deskripsi & Syarat Pre-Order</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">{{ old('description', $preOrder->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">URL Banner Pre-Order</label>
                <input type="url" name="banner_url" value="{{ old('banner_url', $preOrder->banner_path) }}" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Waktu Buka PO</label>
                    <input type="datetime-local" name="starts_at" value="{{ $preOrder->starts_at ? $preOrder->starts_at->format('Y-m-d\TH:i') : '' }}" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Waktu Tutup PO</label>
                    <input type="datetime-local" name="ends_at" value="{{ $preOrder->ends_at ? $preOrder->ends_at->format('Y-m-d\TH:i') : '' }}" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Jadwal Pengambilan</label>
                    <input type="datetime-local" name="pickup_at" value="{{ $preOrder->pickup_at ? $preOrder->pickup_at->format('Y-m-d\TH:i') : '' }}" class="w-full px-3 py-2 rounded-xl border border-[#D8B894] text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Total Kuota (Unit) *</label>
                    <input type="number" name="quota_units" value="{{ old('quota_units', $preOrder->quota_units) }}" required min="{{ $preOrder->reserved_units }}" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                    <span class="text-[10px] text-[#6F4E37] mt-0.5 block">Sudah terpesan: {{ $preOrder->reserved_units }} unit</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#3A2418] mb-1">Status Batch *</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                        <option value="OPEN" {{ old('status', $preOrder->status) == 'OPEN' ? 'selected' : '' }}>OPEN</option>
                        <option value="SCHEDULED" {{ old('status', $preOrder->status) == 'SCHEDULED' ? 'selected' : '' }}>SCHEDULED</option>
                        <option value="CLOSED" {{ old('status', $preOrder->status) == 'CLOSED' ? 'selected' : '' }}>CLOSED</option>
                        <option value="SOLD_OUT" {{ old('status', $preOrder->status) == 'SOLD_OUT' ? 'selected' : '' }}>SOLD_OUT</option>
                        <option value="COMPLETED" {{ old('status', $preOrder->status) == 'COMPLETED' ? 'selected' : '' }}>COMPLETED</option>
                        <option value="CANCELLED" {{ old('status', $preOrder->status) == 'CANCELLED' ? 'selected' : '' }}>CANCELLED</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Instruksi Pengambilan Tambahan</label>
                <input type="text" name="instruction_notes" value="{{ old('instruction_notes', $preOrder->instruction_notes) }}" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('admin.pre-orders.index') }}" class="px-5 py-2.5 rounded-xl border border-[#D8B894] text-xs font-bold text-[#3A2418]">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
