<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard — KopiZ')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        coffee: {
                            primary: '#3A2418',
                            secondary: '#6F4E37',
                            accent: '#B9824A',
                            bg: '#FFF8EF',
                            surface: '#F3E5D0',
                            border: '#D8B894',
                            text: '#2B1B14',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #F8F5F0;
            color: #2B1B14;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col md:flex-row antialiased">
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-[#3A2418] text-[#FFF8EF] shrink-0 flex flex-col justify-between">
        <div>
            <!-- Admin Brand -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-[#6F4E37]/50">
                <div class="w-10 h-10 rounded-xl bg-[#B9824A] text-white flex items-center justify-center font-bold text-lg">
                    ☕
                </div>
                <div>
                    <span class="text-xl font-black block leading-none">KopiZ Admin</span>
                    <span class="text-[10px] text-[#D8B894] font-semibold tracking-wider uppercase block mt-1">Dashboard Operasional</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 text-sm font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#B9824A] text-white shadow-md' : 'text-[#FFF8EF]/80 hover:bg-[#6F4E37]/50 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.orders*') ? 'bg-[#B9824A] text-white shadow-md' : 'text-[#FFF8EF]/80 hover:bg-[#6F4E37]/50 hover:text-white' }}">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span>Pesanan & Order</span>
                </a>

                <a href="{{ route('admin.pre-orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.pre-orders*') ? 'bg-[#B9824A] text-white shadow-md' : 'text-[#FFF8EF]/80 hover:bg-[#6F4E37]/50 hover:text-white' }}">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    <span>Sistem Pre-Order</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.products*') ? 'bg-[#B9824A] text-white shadow-md' : 'text-[#FFF8EF]/80 hover:bg-[#6F4E37]/50 hover:text-white' }}">
                    <i data-lucide="coffee" class="w-5 h-5"></i>
                    <span>Katalog Produk</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.categories*') ? 'bg-[#B9824A] text-white shadow-md' : 'text-[#FFF8EF]/80 hover:bg-[#6F4E37]/50 hover:text-white' }}">
                    <i data-lucide="tag" class="w-5 h-5"></i>
                    <span>Kategori Menu</span>
                </a>

                <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.customers*') ? 'bg-[#B9824A] text-white shadow-md' : 'text-[#FFF8EF]/80 hover:bg-[#6F4E37]/50 hover:text-white' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Data Pelanggan</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.reports*') ? 'bg-[#B9824A] text-white shadow-md' : 'text-[#FFF8EF]/80 hover:bg-[#6F4E37]/50 hover:text-white' }}">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <span>Laporan & Ekspor</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.settings*') ? 'bg-[#B9824A] text-white shadow-md' : 'text-[#FFF8EF]/80 hover:bg-[#6F4E37]/50 hover:text-white' }}">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span>Pengaturan Brand</span>
                </a>
            </nav>
        </div>

        <!-- User / Logout -->
        <div class="p-4 border-t border-[#6F4E37]/50 space-y-3">
            <div class="px-4 py-2">
                <span class="text-xs text-[#D8B894] block">Logged in as:</span>
                <span class="text-sm font-bold text-white block truncate">{{ Auth::user()->name ?? 'Admin KopiZ' }}</span>
                <span class="text-[11px] text-[#FFF8EF]/60 block truncate">{{ Auth::user()->email ?? 'admin@kopiz.id' }}</span>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" target="_blank" class="flex-1 py-2 px-3 rounded-xl bg-[#6F4E37]/60 hover:bg-[#6F4E37] text-xs font-bold text-center text-white transition-all flex items-center justify-center gap-1.5" title="Buka Website Publik">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span>Lihat Web</span>
                </a>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="py-2 px-3 rounded-xl bg-[#B93838]/80 hover:bg-[#B93838] text-xs font-bold text-white transition-all flex items-center justify-center gap-1" title="Keluar">
                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-[#D8B894]/40 px-6 sm:px-8 flex items-center justify-between shrink-0">
            <div>
                <h2 class="text-xl font-black text-[#3A2418]">@yield('header_title', 'Dashboard')</h2>
                <span class="text-xs font-semibold text-[#6F4E37]">Waktu Sistem: {{ now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index', ['status' => 'PENDING']) }}" class="relative p-2.5 rounded-2xl bg-[#FFF8EF] border border-[#D8B894] text-[#3A2418] hover:bg-[#F3E5D0] transition-all" title="Pesanan Pending">
                    <i data-lucide="bell" class="w-4 h-4"></i>
                </a>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 p-6 sm:p-8 overflow-y-auto">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-[#2E7D5B]/15 border border-[#2E7D5B]/30 text-[#2E7D5B] font-medium flex items-center gap-3 text-sm shadow-sm">
                    <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 font-medium flex items-center gap-3 text-sm shadow-sm">
                    <i data-lucide="info" class="w-5 h-5 shrink-0"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-[#B93838]/15 border border-[#B93838]/30 text-[#B93838] font-medium flex items-center gap-3 text-sm shadow-sm">
                    <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
