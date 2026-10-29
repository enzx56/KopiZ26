<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'KopiZ — Kopinya Gen-Z | Kopi Berkualitas, Harga Pas')</title>
    <meta name="description" content="Platform penjualan kopi resmi KopiZ. Kopi berkualitas, harga pas, cocok untuk Gen-Z. Pesan reguler atau ikuti Pre-Order!">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tailwind CSS CDN Fallback for Zero-Config Preview & Standalone Hosting -->
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
                            success: '#2E7D5B',
                            warning: '#B7791F',
                            error: '#B93838',
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
            background-color: #FFF8EF;
            color: #2B1B14;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .badge-cart {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-[#B9824A] selection:text-white">
    <!-- Navbar -->
    <header class="sticky top-0 z-40 bg-[#FFF8EF]/90 backdrop-blur-md border-b border-[#D8B894]/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-[#3A2418] text-[#FFF8EF] flex items-center justify-center font-extrabold text-xl shadow-md group-hover:scale-105 transition-transform">
                    ☕
                </div>
                <div>
                    <span class="text-2xl font-black text-[#3A2418] tracking-tight block leading-tight">KopiZ</span>
                    <span class="text-[11px] font-semibold text-[#B9824A] tracking-wider uppercase block">Kopinya Gen-Z</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-[#3A2418]">
                <a href="{{ route('home') }}" class="hover:text-[#B9824A] transition-colors {{ request()->routeIs('home') ? 'text-[#B9824A]' : '' }}">Beranda</a>
                <a href="{{ route('menu') }}" class="hover:text-[#B9824A] transition-colors {{ request()->routeIs('menu*') ? 'text-[#B9824A]' : '' }}">Menu Kopi</a>
                <a href="{{ route('po') }}" class="hover:text-[#B9824A] transition-colors {{ request()->routeIs('po*') ? 'text-[#B9824A]' : '' }}">Pre-Order (PO)</a>
                <a href="{{ route('order.lookup') }}" class="hover:text-[#B9824A] transition-colors {{ request()->routeIs('order.lookup') ? 'text-[#B9824A]' : '' }}">Lacak Pesanan</a>
                <a href="{{ route('how-to-order') }}" class="hover:text-[#B9824A] transition-colors {{ request()->routeIs('how-to-order') ? 'text-[#B9824A]' : '' }}">Cara Pesan</a>
                <a href="{{ route('about') }}" class="hover:text-[#B9824A] transition-colors {{ request()->routeIs('about') ? 'text-[#B9824A]' : '' }}">Tentang</a>
                <a href="{{ route('contact') }}" class="hover:text-[#B9824A] transition-colors {{ request()->routeIs('contact') ? 'text-[#B9824A]' : '' }}">Kontak</a>
            </nav>

            <!-- Actions (Cart & Customer Auth) -->
            <div class="flex items-center gap-2.5">
                <a href="{{ route('cart') }}" class="relative inline-flex items-center gap-2 px-3.5 py-2 rounded-full bg-[#F3E5D0] hover:bg-[#D8B894]/70 text-[#3A2418] text-xs sm:text-sm font-bold transition-all shadow-sm">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Keranjang</span>
                    <span id="cart-count" class="badge-cart w-5 h-5 rounded-full bg-[#3A2418] text-white text-xs font-bold">0</span>
                </a>

                @if(Auth::guard('customer')->check())
                    <a href="{{ route('customer.orders') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold shadow-sm transition-all" title="Akun Pelanggan KopiZ">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                        <span class="max-w-[100px] truncate">{{ Str::limit(Auth::guard('customer')->user()->name, 12) }}</span>
                    </a>
                @else
                    <a href="{{ route('customer.login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-[#3A2418] hover:bg-[#6F4E37] text-white text-xs font-bold shadow-sm transition-all">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                        <span>Masuk</span>
                    </a>
                @endif

                <a href="{{ route('admin.login') }}" class="text-xs font-semibold text-[#6F4E37] hover:text-[#3A2418] px-2 py-1 rounded transition-colors hidden xl:inline-block" title="Login Admin KopiZ">
                    Admin
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="p-4 rounded-2xl bg-[#2E7D5B]/15 border border-[#2E7D5B]/30 text-[#2E7D5B] font-medium flex items-center gap-3 shadow-sm">
                    <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="p-4 rounded-2xl bg-[#B93838]/15 border border-[#B93838]/30 text-[#B93838] font-medium flex items-center gap-3 shadow-sm">
                    <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#3A2418] text-[#FFF8EF] border-t border-[#6F4E37]/50 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Col 1: Brand Info -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#B9824A] text-white flex items-center justify-center font-bold text-lg">
                            ☕
                        </div>
                        <span class="text-2xl font-black tracking-tight">KopiZ</span>
                    </div>
                    <p class="text-xs text-[#FFF8EF]/80 font-semibold tracking-wider uppercase text-[#B9824A]">
                        {{ $settings['brand_tagline'] ?? 'Kopinya Gen-Z' }}
                    </p>
                    <p class="text-sm text-[#FFF8EF]/70 leading-relaxed">
                        {{ $settings['brand_description'] ?? 'Kedai kopi pilihan generasi muda dengan bahan berkualitas premium, racikan pas, dan harga ramah di kantong.' }}
                    </p>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div>
                    <h4 class="text-sm font-bold text-[#B9824A] uppercase tracking-wider mb-4">Navigasi</h4>
                    <ul class="space-y-2.5 text-sm text-[#FFF8EF]/80">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('menu') }}" class="hover:text-white transition-colors">Katalog Menu</a></li>
                        <li><a href="{{ route('po') }}" class="hover:text-white transition-colors">Pre-Order Spesial</a></li>
                        <li><a href="{{ route('how-to-order') }}" class="hover:text-white transition-colors">Cara Pemesanan</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Col 3: Jam & Lokasi -->
                <div>
                    <h4 class="text-sm font-bold text-[#B9824A] uppercase tracking-wider mb-4">Operasional & Lokasi</h4>
                    <div class="space-y-3 text-sm text-[#FFF8EF]/80">
                        <div class="flex items-start gap-2.5">
                            <i data-lucide="clock" class="w-4 h-4 text-[#B9824A] shrink-0 mt-0.5"></i>
                            <div>
                                <span class="font-semibold block text-white">Jam Operasional:</span>
                                <span>{{ $settings['operational_hours'] ?? 'Setiap Hari: 09:00 - 22:00 WIB' }}</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#B9824A] shrink-0 mt-0.5"></i>
                            <div>
                                <span class="font-semibold block text-white">Lokasi Pickup:</span>
                                <span>{{ $settings['pickup_address'] ?? 'Jl. Pemuda No. 45, Kota Serang' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Col 4: Kontak & Kebijakan -->
                <div>
                    <h4 class="text-sm font-bold text-[#B9824A] uppercase tracking-wider mb-4">Kontak & Bantuan</h4>
                    <ul class="space-y-2.5 text-sm text-[#FFF8EF]/80">
                        <li>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '6281234567890') }}" target="_blank" class="hover:text-white flex items-center gap-2">
                                <i data-lucide="phone" class="w-4 h-4 text-[#B9824A]"></i>
                                <span>WhatsApp: {{ $settings['whatsapp_number'] ?? '+62 812-3456-7890' }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ $settings['instagram_url'] ?? 'https://instagram.com/kopiz.id' }}" target="_blank" class="hover:text-white flex items-center gap-2">
                                <i data-lucide="instagram" class="w-4 h-4 text-[#B9824A]"></i>
                                <span>{{ $settings['instagram_handle'] ?? '@kopiz.id' }}</span>
                            </a>
                        </li>
                        <li class="pt-2"><a href="{{ route('privacy') }}" class="hover:text-white text-xs text-[#FFF8EF]/60">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-white text-xs text-[#FFF8EF]/60">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-[#6F4E37]/40 flex flex-col sm:flex-row items-center justify-between text-xs text-[#FFF8EF]/60 gap-4">
                <p>&copy; {{ date('Y') }} KopiZ (Kopinya Gen-Z). Seluruh hak cipta dilindungi.</p>
                <p>Kopi Berkualitas, Harga Pas ☕</p>
            </div>
        </div>
    </footer>

    <!-- Cart Script -->
    <script>
        lucide.createIcons();

        // LocalStorage Cart Client Helper
        const KopiZCart = {
            getKey: () => 'kopiz_cart_items',
            getItems: function() {
                try {
                    return JSON.parse(localStorage.getItem(this.getKey())) || [];
                } catch (e) {
                    return [];
                }
            },
            saveItems: function(items) {
                localStorage.setItem(this.getKey(), JSON.stringify(items));
                this.updateBadge();
            },
            addItem: function(product, qty = 1) {
                let items = this.getItems();
                let existing = items.find(i => i.id === product.id);
                if (existing) {
                    existing.quantity += qty;
                } else {
                    items.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        image: product.image,
                        quantity: qty
                    });
                }
                this.saveItems(items);
            },
            removeItem: function(id) {
                let items = this.getItems().filter(i => i.id !== id);
                this.saveItems(items);
            },
            updateQuantity: function(id, qty) {
                let items = this.getItems();
                let item = items.find(i => i.id === id);
                if (item) {
                    item.quantity = Math.max(1, qty);
                    this.saveItems(items);
                }
            },
            clear: function() {
                localStorage.removeItem(this.getKey());
                this.updateBadge();
            },
            count: function() {
                return this.getItems().reduce((sum, i) => sum + i.quantity, 0);
            },
            updateBadge: function() {
                const el = document.getElementById('cart-count');
                if (el) {
                    el.innerText = this.count();
                }
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            KopiZCart.updateBadge();
        });
    </script>
    @stack('scripts')
</body>
</html>
