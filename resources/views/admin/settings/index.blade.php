@extends('layouts.admin')

@section('title', 'Pengaturan Brand & Konten — KopiZ Admin')
@section('header_title', 'Pengaturan Brand, Kontak & Konten')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#D8B894]/60 shadow-sm">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <h3 class="text-base font-black text-[#3A2418] mb-4 pb-2 border-b border-[#D8B894]/40">Identitas Brand KopiZ</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Nama Brand</label>
                        <input type="text" name="brand_name" value="{{ old('brand_name', $settings['brand_name'] ?? 'KopiZ') }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Tagline</label>
                        <input type="text" name="brand_tagline" value="{{ old('brand_tagline', $settings['brand_tagline'] ?? 'Kopinya Gen-Z') }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Moto Brand</label>
                        <input type="text" name="brand_motto" value="{{ old('brand_motto', $settings['brand_motto'] ?? 'Kopi Berkualitas, Harga Pas') }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Deskripsi Singkat</label>
                        <input type="text" name="brand_description" value="{{ old('brand_description', $settings['brand_description'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-base font-black text-[#3A2418] mb-4 pb-2 border-b border-[#D8B894]/40">Kontak Resmi & Media Sosial</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Nomor WhatsApp Bisnis *</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '6281234567890') }}" required placeholder="Contoh: 6281234567890" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                        <span class="text-[10px] text-[#6F4E37] block mt-0.5">Digunakan sebagai tujuan pemesanan checkout pelanggan. Gunakan format internasional (62...).</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Username Instagram</label>
                        <input type="text" name="instagram_handle" value="{{ old('instagram_handle', $settings['instagram_handle'] ?? '@kopiz.id') }}" placeholder="@kopiz.id" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">URL Profil Instagram</label>
                        <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? 'https://instagram.com/kopiz.id') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-base font-black text-[#3A2418] mb-4 pb-2 border-b border-[#D8B894]/40">Operasional & Lokasi Kedai</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Jam Operasional</label>
                        <input type="text" name="operational_hours" value="{{ old('operational_hours', $settings['operational_hours'] ?? 'Senin - Minggu: 09:00 - 22:00 WIB') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Alamat Titik Pickup</label>
                        <input type="text" name="pickup_address" value="{{ old('pickup_address', $settings['pickup_address'] ?? 'Jl. Pemuda No. 45, Kota Serang, Banten') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-base font-black text-[#3A2418] mb-4 pb-2 border-b border-[#D8B894]/40">Teks Banner Beranda (Hero)</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Judul Utama Beranda (Hero Title)</label>
                        <input type="text" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#D8B894] text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#3A2418] mb-1">Subjudul Beranda (Hero Subtitle)</label>
                        <textarea name="hero_subtitle" rows="2" class="w-full px-4 py-2 rounded-xl border border-[#D8B894] text-xs">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-[#D8B894]/40 flex justify-end">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold shadow-md transition-all">
                    Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
