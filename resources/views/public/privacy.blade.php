@extends('layouts.public')

@section('title', 'Kebijakan Privasi — KopiZ')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="bg-white rounded-3xl border border-[#D8B894] p-8 sm:p-12 shadow-sm space-y-6 text-[#3A2418]">
        <h1 class="text-3xl font-black">Kebijakan Privasi KopiZ</h1>
        <p class="text-xs text-[#6F4E37]">Terakhir diperbarui: Oktober 2026</p>

        <div class="prose text-sm leading-relaxed space-y-4 text-[#6F4E37]">
            <p>
                KopiZ menghormati dan berkomitmen melindungi privasi pengunjung serta pelanggan kami sesuai prinsip PRD KopiZ Bagian 16 dan 21. Dokumen ini menjelaskan pengumpulan dan pemrosesan data di platform kami.
            </p>
            <h3 class="text-base font-bold text-[#3A2418]">1. Data yang Dikumpulkan</h3>
            <p>
                Kami mengumpulkan data minimum yang diperlukan saat proses checkout: nama pemesan dan nomor telepon/WhatsApp. Kami tidak meminta kata sandi akun pelanggan atau data finansial sensitif pada saat checkout tanpa akun.
            </p>
            <h3 class="text-base font-bold text-[#3A2418]">2. Penggunaan Data</h3>
            <p>
                Informasi yang Anda berikan semata-mata digunakan untuk:
            </p>
            <ul class="list-disc pl-5 space-y-1">
                <li>Mengidentifikasi pemesan dan meracik menu yang dipesan.</li>
                <li>Menghubungi Anda via WhatsApp untuk konfirmasi status dan jam pengambilan.</li>
                <li>Pencatatan riwayat transaksi internal bagi keperluan pelaporan operasional.</li>
            </ul>
            <h3 class="text-base font-bold text-[#3A2418]">3. Keamanan Data</h3>
            <p>
                Akses ke data nomor kontak pelanggan dibatasi secara ketat hanya untuk admin yang berwenang. Kami tidak pernah memperjualbelikan atau mendistribusikan data kontak pelanggan kepada pihak ketiga mana pun.
            </p>
        </div>
    </div>
</div>
@endsection
