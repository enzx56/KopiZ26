@extends('layouts.admin')

@section('title', 'Tambah Admin Baru — KopiZ Admin')
@section('header_title', 'Tambah Akun Admin Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-[#6F4E37] hover:text-[#3A2418] flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Admin</span>
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#D8B894]/70 shadow-sm space-y-6">
        <div>
            <h2 class="text-xl font-black text-[#3A2418]">Form Tambah Admin Baru</h2>
            <p class="text-xs text-[#6F4E37]">Buat akun baru untuk rekan kerja atau staf operasional KopiZ</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Nama Lengkap Admin *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Barista Budi" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('name')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Email Login Admin *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: budi@kopiz.id" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('email')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Password *</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('password')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password di atas" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-xs shadow-md transition-all">
                    Simpan Admin Baru
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-2xl bg-[#F3E5D0] hover:bg-[#D8B894] text-[#3A2418] font-bold text-xs transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
