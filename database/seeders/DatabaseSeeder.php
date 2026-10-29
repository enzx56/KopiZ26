<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\PreOrder;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@kopiz.id'],
            [
                'name' => 'Admin KopiZ',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Settings
        $settings = [
            'brand_name' => ['KopiZ', true],
            'brand_tagline' => ['Kopinya Gen-Z', true],
            'brand_motto' => ['Kopi Berkualitas, Harga Pas', true],
            'brand_description' => ['Kedai kopi pilihan generasi muda dengan bahan berkualitas premium, racikan pas, dan harga ramah di kantong.', true],
            'whatsapp_number' => ['6281234567890', true],
            'instagram_handle' => ['@kopiz.id', true],
            'instagram_url' => ['https://instagram.com/kopiz.id', true],
            'operational_hours' => ['Senin - Minggu: 09:00 - 22:00 WIB', true],
            'pickup_address' => ['Jl. Pemuda No. 45, Kota Serang, Banten (Dekat Alun-alun)', true],
            'hero_title' => ['Kopi Asli Indonesia, Rasa Pas di Hati & Kantong', true],
            'hero_subtitle' => ['Temani setiap sesi tugas, nongkrong, dan lemburmu dengan racikan kopi istimewa KopiZ.', true],
        ];

        foreach ($settings as $key => [$value, $isPublic]) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'is_public' => $isPublic]
            );
        }

        // 3. Categories
        $catSignature = Category::firstOrCreate(
            ['slug' => 'signature-coffee'],
            ['name' => 'Signature Coffee', 'description' => 'Kopi racikan khas KopiZ yang paling dicintai', 'sort_order' => 1]
        );

        $catEspresso = Category::firstOrCreate(
            ['slug' => 'espresso-based'],
            ['name' => 'Espresso Based', 'description' => 'Menu kopi klasik kaya rasa', 'sort_order' => 2]
        );

        $catNonCoffee = Category::firstOrCreate(
            ['slug' => 'non-coffee'],
            ['name' => 'Non-Coffee', 'description' => 'Minuman segar dan manis non-kopi', 'sort_order' => 3]
        );

        // 4. Products (The 3 initial products from PRD Section 8)
        $butterscotch = Product::firstOrCreate(
            ['slug' => 'butterscotch'],
            [
                'category_id' => $catSignature->id,
                'sku' => 'KPZ-SIG-001',
                'name' => 'Butterscotch',
                'short_description' => 'Kopi creamy dengan rasa butterscotch yang manis dan gurih',
                'description' => 'Perpaduan sempurna antara double shot espresso pilihan, susu segar lembut, dan sirup butterscotch premium yang meleleh di lidah. Manisnya pas, aromanya memikat!',
                'image_path' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=600&auto=format&fit=crop&q=80',
                'price' => 15000,
                'status' => 'READY',
                'track_inventory' => true,
                'stock_quantity' => 50,
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        $gulaAren = Product::firstOrCreate(
            ['slug' => 'kopi-susu-gula-aren'],
            [
                'category_id' => $catSignature->id,
                'sku' => 'KPZ-SIG-002',
                'name' => 'Kopi Susu Gula Aren',
                'short_description' => 'Kopi susu dengan rasa gula aren asli yang khas dan legit',
                'description' => 'Favorit sejuta umat! Espresso kental bersatu dengan susu creamy dan gula aren murni organik dari petani lokal. Rasa otentik yang tidak pernah salah.',
                'image_path' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=600&auto=format&fit=crop&q=80',
                'price' => 15000,
                'status' => 'READY',
                'track_inventory' => true,
                'stock_quantity' => 75,
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        $saltedCaramel = Product::firstOrCreate(
            ['slug' => 'salted-caramel'],
            [
                'category_id' => $catSignature->id,
                'sku' => 'KPZ-SIG-003',
                'name' => 'Salted Caramel',
                'short_description' => 'Kopi dengan perpaduan caramel harum dan sentuhan asin gurih',
                'description' => 'Sensasi unik perpaduan rasa karamel manis legit dengan hint garam laut (sea salt) yang menyeimbangkan rasa, berpadu dengan espresso bold dan susu creamy dingin.',
                'image_path' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=600&auto=format&fit=crop&q=80',
                'price' => 17000,
                'status' => 'READY',
                'track_inventory' => true,
                'stock_quantity' => 40,
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        // 5. Pre-Order Batch
        $poBatch = PreOrder::firstOrCreate(
            ['slug' => 'batch-spesial-weekend-genz'],
            [
                'name' => 'Batch Spesial Weekend Gen-Z: Literan Series',
                'description' => 'Pre-order kopi botol 1 Liter favoritmu untuk stok santai di rumah atau bareng teman. Fresh diseduh sebelum jadwal pengambilan!',
                'banner_path' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1200&auto=format&fit=crop&q=80',
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays(3),
                'pickup_at' => now()->addDays(4)->setHour(14)->setMinute(0),
                'quota_units' => 30,
                'reserved_units' => 6,
                'status' => 'OPEN',
                'instruction_notes' => 'Pengambilan dilakukan di kedai KopiZ mulai pukul 14:00 - 18:00 WIB. Tunjukkan kode order kepada kasir.',
                'published_at' => now()->subDay(),
                'created_by' => $admin->id,
            ]
        );

        if (!$poBatch->products()->where('product_id', $butterscotch->id)->exists()) {
            $poBatch->products()->attach($butterscotch->id, ['price' => 14000, 'max_quantity_per_order' => 5]);
            $poBatch->products()->attach($saltedCaramel->id, ['price' => 16000, 'max_quantity_per_order' => 5]);
        }
    }
}
