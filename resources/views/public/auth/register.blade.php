@extends('layouts.public')

@section('title', 'Daftar Akun Pelanggan — KopiZ')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 py-12">
    <div class="bg-white p-8 rounded-3xl border border-[#D8B894]/70 shadow-sm space-y-6">
        <div class="text-center space-y-1">
            <div class="w-12 h-12 rounded-2xl bg-[#3A2418] text-white flex items-center justify-center font-extrabold text-xl mx-auto shadow-sm">
                ☕
            </div>
            <h1 class="text-2xl font-black text-[#3A2418] tracking-tight pt-2">Daftar Akun KopiZ</h1>
            <p class="text-xs text-[#6F4E37]">Buat akun untuk melacak seluruh pesanan kopi Anda dengan mudah.</p>
        </div>

        <form action="{{ route('customer.register.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Rian Pratama" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('name')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Nomor WhatsApp Aktif *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="Contoh: 081234567890" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                <span class="text-[10px] text-[#6F4E37] mt-1 block">Nomor ini akan terhubung langsung dengan konfirmasi pesanan Anda.</span>
                @error('phone')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Email (Opsional)</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: rian@gmail.com" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('email')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Username Instagram (Opsional)</label>
                <input type="text" name="instagram_handle" value="{{ old('instagram_handle') }}" placeholder="Contoh: @rianpratama" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Buat Password *</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('password')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password di atas" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-black text-sm shadow-md transition-all">
                Daftar Sekarang
            </button>
        </form>

        <div class="pt-4 border-t border-[#D8B894]/40 text-center text-xs text-[#6F4E37]">
            Sudah punya akun?
            <a href="{{ route('customer.login') }}" class="font-bold text-[#B9824A] hover:underline">Masuk ke Akun Anda</a>
        </div>
    </div>
</div>
@endsection
