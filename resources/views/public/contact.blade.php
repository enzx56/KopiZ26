@extends('layouts.public')

@section('title', 'Kontak & Lokasi — KopiZ')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="bg-white rounded-3xl border border-[#D8B894] p-8 sm:p-12 shadow-sm space-y-8">
        <div class="text-center space-y-3">
            <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Hubungi Kami</span>
            <h1 class="text-3xl sm:text-4xl font-black text-[#3A2418]">Kontak & Alamat KopiZ</h1>
            <p class="text-sm text-[#6F4E37] max-w-xl mx-auto">
                Punya pertanyaan, pemesanan katering, atau masukan untuk KopiZ? Kami siap menyapa Anda!
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/50 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#2E7D5B]/15 text-[#2E7D5B] flex items-center justify-center font-bold">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#3A2418]">WhatsApp Customer Service</h3>
                        <p class="text-xs text-[#6F4E37]">Fast response jam kerja</p>
                    </div>
                </div>
                <p class="text-base font-black text-[#3A2418]">{{ $settings['whatsapp_number'] ?? '+62 812-3456-7890' }}</p>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '6281234567890') }}" target="_blank" class="inline-block px-5 py-2 rounded-xl bg-[#2E7D5B] text-white font-bold text-xs">
                    Chat WhatsApp
                </a>
            </div>

            <div class="p-6 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/50 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#B9824A]/15 text-[#B9824A] flex items-center justify-center font-bold">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#3A2418]">Instagram Resmi</h3>
                        <p class="text-xs text-[#6F4E37]">Update promo & event</p>
                    </div>
                </div>
                <p class="text-base font-black text-[#3A2418]">{{ $settings['instagram_handle'] ?? '@kopiz.id' }}</p>
                <a href="{{ $settings['instagram_url'] ?? 'https://instagram.com/kopiz.id' }}" target="_blank" class="inline-block px-5 py-2 rounded-xl bg-[#3A2418] text-white font-bold text-xs">
                    Follow Instagram
                </a>
            </div>

            <div class="p-6 rounded-2xl bg-[#FFF8EF] border border-[#D8B894]/50 space-y-2 md:col-span-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-5 h-5 text-[#B9824A]"></i>
                    <h3 class="font-bold text-[#3A2418]">Alamat Kedai & Titik Pengambilan (Pickup)</h3>
                </div>
                <p class="text-sm text-[#6F4E37] leading-relaxed">
                    {{ $settings['pickup_address'] ?? 'Jl. Pemuda No. 45, Kota Serang, Banten' }}
                </p>
                <div class="flex items-center gap-2 text-xs font-semibold text-[#3A2418] pt-2">
                    <i data-lucide="clock" class="w-4 h-4 text-[#B9824A]"></i>
                    <span>Jam Buka: {{ $settings['operational_hours'] ?? '09:00 - 22:00 WIB' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
