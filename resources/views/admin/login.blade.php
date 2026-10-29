<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — KopiZ</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            background-color: #FFF8EF;
            color: #2B1B14;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white p-8 sm:p-10 rounded-3xl border border-[#D8B894] shadow-xl space-y-6">
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-[#3A2418] text-[#FFF8EF] flex items-center justify-center font-black text-2xl mx-auto shadow-md">
                ☕
            </div>
            <h1 class="text-2xl font-black text-[#3A2418]">Login Admin KopiZ</h1>
            <p class="text-xs text-[#6F4E37]">Akses manajemen katalog, pesanan, dan laporan bisnis</p>
        </div>

        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-[#2E7D5B]/15 border border-[#2E7D5B]/30 text-[#2E7D5B] text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-3.5 rounded-xl bg-[#B93838]/15 border border-[#B93838]/30 text-[#B93838] text-xs font-semibold">
                @foreach($errors->all() as $err)
                    <p>{{ $err }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Email Admin</label>
                <input type="email" name="email" value="{{ old('email', 'admin@kopiz.id') }}" required class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#3A2418] mb-1">Password</label>
                <input type="password" name="password" required value="admin123" class="w-full px-4 py-3 rounded-2xl border border-[#D8B894] text-sm text-[#3A2418] focus:outline-none focus:border-[#B9824A] bg-[#FFF8EF]">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-[#6F4E37]">
                    <input type="checkbox" name="remember" class="rounded text-[#3A2418] focus:ring-[#B9824A]">
                    <span>Ingat Sesi Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-[#3A2418] hover:bg-[#6F4E37] text-white font-bold text-sm shadow-md transition-all">
                Masuk ke Dashboard
            </button>
        </form>

        <div class="pt-4 border-t border-[#D8B894]/40 text-center">
            <a href="{{ route('home') }}" class="text-xs font-bold text-[#6F4E37] hover:text-[#3A2418] flex items-center justify-center gap-1.5">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Website Publik</span>
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
