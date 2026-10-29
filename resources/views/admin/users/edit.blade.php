@extends('layouts.admin')

@section('title', 'Edit Admin — KopiZ Admin')
@section('header_title', 'Edit Akun Admin')

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
            <h2 class="text-xl font-black text-[#3A2418]">Edit Akun Admin: {{ $user->name }}</h2>
            <p class="text-xs text-[#6F4E37]">Perbarui informasi nama, email, atau ubah password admin</p>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Nama Lengkap Admin *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('name')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Email Login Admin *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('email')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="pt-2 border-t border-[#D8B894]/40">
                <span class="text-xs font-bold text-[#B9824A] block mb-2">Ubah Password (Kosongkan bila tidak ingin mengganti password)</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Password Baru (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
                @error('password')
                    <span class="text-[11px] text-[#B93838] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru jika diubah" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-xs shadow-md transition-all">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-2xl bg-[#F3E5D0] hover:bg-[#D8B894] text-[#3A2418] font-bold text-xs transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
