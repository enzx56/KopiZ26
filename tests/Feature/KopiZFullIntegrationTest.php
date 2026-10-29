<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\PreOrder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KopiZFullIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Uji 1: Halaman publik dapat diakses dengan data produk dari database.
     */
    public function test_public_pages_render_with_seeded_products(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('KopiZ');
        $response->assertSee('Kopinya Gen-Z');
        $response->assertSee('Butterscotch');

        $menuResponse = $this->get('/menu');
        $menuResponse->assertStatus(200);
        $menuResponse->assertSee('Butterscotch');
        $menuResponse->assertSee('Kopi Susu Gula Aren');
        $menuResponse->assertSee('Salted Caramel');

        $poResponse = $this->get('/po');
        $poResponse->assertStatus(200);
        $poResponse->assertSee('Batch Spesial Weekend Gen-Z');
    }

    /**
     * Uji 2: Checkout reguler tanpa akun menyimpan order & item dengan snapshot harga server.
     */
    public function test_regular_checkout_flow_saves_order_atomically(): void
    {
        $butterscotch = Product::where('slug', 'butterscotch')->first();

        $postData = [
            'customer_name' => 'Aditya Pratama',
            'customer_phone' => '081298765432',
            'order_type' => 'REGULAR',
            'pickup_at' => '15:30 WIB',
            'customer_notes' => 'Less sugar please',
            'items' => [
                [
                    'product_id' => $butterscotch->id,
                    'quantity' => 2,
                ]
            ]
        ];

        $response = $this->post('/checkout', $postData);
        $response->assertRedirect();

        $this->assertDatabaseHas('customers', [
            'name' => 'Aditya Pratama',
            'normalized_phone' => '6281298765432',
        ]);

        $this->assertDatabaseHas('orders', [
            'order_type' => 'REGULAR',
            'status' => 'PENDING',
            'subtotal' => 30000,
            'total_amount' => 30000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_name_snapshot' => 'Butterscotch',
            'unit_price' => 15000,
            'quantity' => 2,
            'subtotal' => 30000,
        ]);
    }

    /**
     * Uji 3: Pre-Order checkout menghitung kuota dan menolak jika melebihi kuota.
     */
    public function test_pre_order_quota_validation_and_decrement(): void
    {
        $po = PreOrder::where('status', 'OPEN')->first();
        $product = $po->products->first();

        // 1. Pesan dalam batas kuota
        $postData = [
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '085511223344',
            'order_type' => 'PRE_ORDER',
            'pre_order_id' => $po->id,
            'pickup_at' => '14:00 WIB',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                ]
            ]
        ];

        $initialReserved = $po->reserved_units;
        $response = $this->post('/checkout', $postData);
        $response->assertRedirect();

        $po->refresh();
        $this->assertEquals($initialReserved + 3, $po->reserved_units);

        // 2. Pesan melebihi sisa kuota harus ditolak server
        $exceedingData = [
            'customer_name' => 'Chandra Wijaya',
            'customer_phone' => '087799887766',
            'order_type' => 'PRE_ORDER',
            'pre_order_id' => $po->id,
            'pickup_at' => '14:00 WIB',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $po->quota_units + 10,
                ]
            ]
        ];

        $exceedResponse = $this->post('/checkout', $exceedingData);
        $exceedResponse->assertSessionHasErrors(['msg']);
    }

    /**
     * Uji 4: Akses admin terlindungi dan autentikasi berjalan aman.
     */
    public function test_admin_authentication_and_route_protection(): void
    {
        // Tanpa login harus redirect ke login admin
        $guestResponse = $this->get('/admin');
        $guestResponse->assertRedirect('/admin/login');

        // Login dengan admin yang benar
        $loginResponse = $this->post('/admin/login', [
            'email' => 'admin@kopiz.id',
            'password' => 'admin123',
        ]);
        $loginResponse->assertRedirect('/admin');

        $this->assertAuthenticated();

        // Akses dashboard setelah login
        $adminDashboardResponse = $this->get('/admin');
        $adminDashboardResponse->assertStatus(200);
        $adminDashboardResponse->assertSee('Ringkasan Operasional & Bisnis');
    }

    /**
     * Uji 5: Ekspor Laporan CSV menghasilkan header UTF-8 BOM dan mencakup data filter.
     */
    public function test_report_csv_export_format(): void
    {
        $admin = User::first();
        $response = $this->actingAs($admin)->get('/admin/reports/export-csv');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        
        $content = $response->streamedContent();
        // Cek UTF-8 BOM
        $this->assertStringStartsWith("\xEF\xBB\xBF", $content);
        $this->assertStringContainsString('Kode Pesanan', $content);
    }
}
