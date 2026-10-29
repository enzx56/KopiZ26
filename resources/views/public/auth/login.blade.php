@extends('layouts.public')

@section('title', 'Masuk Akun Pelanggan — KopiZ')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 py-12">
    <div class="bg-white p-8 rounded-3xl border border-[#D8B894]/70 shadow-sm space-y-6">
        <div class="text-center space-y-1">
            <div class="w-12 h-12 rounded-2xl bg-[#3A2418] text-white flex items-center justify-center font-extrabold text-xl mx-auto shadow-sm">
                ☕
            </div>
            <h1 class="text-2xl font-black text-[#3A2418] tracking-tight pt-2">Masuk ke KopiZ</h1>
            <p class="text-xs text-[#6F4E37]">Masukkan nomor WhatsApp atau Email terdaftar untuk melihat pesanan Anda.</p>
        </div>

        <form action="{{ route('customer.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Nomor WhatsApp atau Email *</label>
                <input type="text" name="login_id" value="{{ old('login_id') }}" required placeholder="Contoh: 081234567890 atau email@anda.com" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('login_id')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-bold text-[#3A2418]">Password *</label>
                </div>
                <input type="password" name="password" required placeholder="Masukkan password Anda" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('password')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-[#6F4E37]">
                    <input type="checkbox" name="remember" class="rounded text-[#3A2418] focus:ring-[#B9824A]">
                    <span>Ingat Saya</span>
                </label>
                <a href="{{ route('order.lookup') }}" class="text-[#B9824A] hover:underline font-semibold">Lupa Password? Cek via No HP</a>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-black text-sm shadow-md transition-all">
                Masuk Sekarang
            </button>
        </form>

        <div class="pt-4 border-t border-[#D8B894]/40 text-center text-xs text-[#6F4E37] space-y-2">
            <div>
                Belum punya akun?
                <a href="{{ route('customer.register') }}" class="font-bold text-[#B9824A] hover:underline">Daftar Akun Baru</a>
            </div>
            <div>
                Atau ingin langsung lacak kode pesanan?
                <a href="{{ route('order.lookup') }}" class="font-bold text-[#3A2418] hover:underline">Lacak Pesanan Cepat &rarr;</a>
            </div>
        </div>
    </div>
</div>
@endsection
