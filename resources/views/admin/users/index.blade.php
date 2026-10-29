@extends('layouts.admin')

@section('title', 'Kelola Admin — KopiZ Admin')
@section('header_title', 'Manajemen Pengguna Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-[#3A2418]">Daftar Akun Admin</h2>
            <p class="text-xs text-[#6F4E37]">Kelola siapa saja yang memiliki akses login ke Dashboard Admin KopiZ</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-xs flex items-center gap-2 shadow-sm transition-all">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Tambah Admin Baru</span>
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-5 rounded-3xl border border-[#D8B894]/60 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3 items-center">
            <div class="relative flex-grow w-full">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email admin..." class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-[#D8B894] text-xs focus:outline-none focus:border-[#B9824A]">
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#3A2418] text-white text-xs font-bold">Cari</button>
            @if(request('q'))
                <a href="{{ route('admin.users.index') }}" class="p-2.5 rounded-xl bg-[#F3E5D0] text-[#3A2418]" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Admin -->
    <div class="bg-white rounded-3xl border border-[#D8B894]/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FFF8EF] border-b border-[#D8B894]/40 text-[#6F4E37] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Nama Admin</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Terdaftar Sejak</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D8B894]/30">
                    @forelse($admins as $adm)
                        <tr class="hover:bg-[#FFF8EF]/40">
                            <td class="py-3.5 px-4 font-bold text-sm text-[#3A2418] flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-[#3A2418] text-white flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($adm->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span>{{ $adm->name }}</span>
                                    @if(Auth::id() === $adm->id)
                                        <span class="ml-1.5 px-2 py-0.5 rounded-full bg-[#2E7D5B]/15 text-[#2E7D5B] text-[10px] font-bold">Akun Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-[#6F4E37] font-mono text-xs">
                                {{ $adm->email }}
                            </td>
                            <td class="py-3.5 px-4 text-[#6F4E37] text-[11px]">
                                {{ $adm->created_at ? $adm->created_at->format('d M Y, H:i') : '-' }} WIB
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.users.edit', $adm->id) }}" class="p-1.5 rounded-lg bg-[#F3E5D0] hover:bg-[#D8B894] text-[#3A2418] font-bold transition-all" title="Edit Admin">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>

                                    @if(Auth::id() !== $adm->id && $admins->total() > 1)
                                        <form action="{{ route('admin.users.destroy', $adm->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin {{ $adm->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-[#B93838]/15 hover:bg-[#B93838]/25 text-[#B93838] font-bold transition-all" title="Hapus Admin">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-[#6F4E37]">Tidak ada data admin yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#D8B894]/30">
            {{ $admins->links() }}
        </div>
    </div>
</div>
@endsection
