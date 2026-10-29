@extends('layouts.public')

@section('title', 'Tentang KopiZ — Kopinya Gen-Z')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="bg-white rounded-3xl border border-[#D8B894] p-8 sm:p-12 shadow-sm space-y-8">
        <div class="text-center space-y-3">
            <span class="text-xs font-bold text-[#B9824A] uppercase tracking-wider block">Cerita Kami</span>
            <h1 class="text-3xl sm:text-4xl font-black text-[#3A2418]">Tentang KopiZ</h1>
            <p class="text-base text-[#6F4E37] max-w-xl mx-auto font-medium">
                {{ $settings['brand_motto'] ?? 'Kopi Berkualitas, Harga Pas' }}
            </p>
        </div>

        <div class="prose text-[#3A2418] text-sm leading-relaxed space-y-4">
            <p>
                <strong>KopiZ</strong> hadir berangkat dari keresahan sederhana anak muda, pelajar, mahasiswa, dan pekerja kreatif: sulitnya menemukan kopi dengan cita rasa otentik yang berkualitas namun ramah di dompet.
            </p>
            <p>
                Kami memadukan biji kopi Nusantara pilihan dengan resep racikan modern yang creamy, gurih, dan aromatik. Dari varian legendaris <em>Butterscotch</em>, <em>Kopi Susu Gula Aren</em> murni, hingga sensasi unik <em>Salted Caramel</em>, setiap tetes kami racik dengan dedikasi tinggi langsung dari kedai kami.
            </p>
            <h3 class="text-lg font-bold text-[#3A2418] pt-4">Nilai Brand KopiZ</h3>
            <ul class="list-disc pl-5 space-y-2 text-[#6F4E37]">
                <li><strong>Kualitas Tanpa Kompromi:</strong> Menggunakan biji kopi asli tanpa campuran esens buatan yang berlebihan.</li>
                <li><strong>Harga Pas di Kantong:</strong> Menyesuaikan daya beli Gen-Z tanpa mengurangi takaran rasa.</li>
                <li><strong>Transparansi & Kemudahan:</strong> Sistem pemesanan web yang langsung terhubung ke WhatsApp tanpa biaya tersembunyi.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
