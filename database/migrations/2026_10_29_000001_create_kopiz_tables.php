<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('sku')->nullable()->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('price')->default(0); // Rupiah as integer
            $table->string('status')->default('READY'); // READY, PO, SOLD_OUT, INACTIVE
            $table->boolean('track_inventory')->default(false);
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'is_active']);
            $table->index('is_featured');
        });

        // 3. Customers (derived from checkout)
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('normalized_phone')->index();
            $table->string('instagram_handle')->nullable();
            $table->timestamps();
        });

        // 4. Pre-Orders
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('banner_path')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('pickup_at')->nullable();
            $table->integer('quota_units')->default(0);
            $table->integer('reserved_units')->default(0);
            $table->string('status')->default('DRAFT'); // DRAFT, SCHEDULED, OPEN, CLOSED, SOLD_OUT, COMPLETED, CANCELLED
            $table->text('instruction_notes')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
        });

        // 5. Pre-Order Products (pivot table)
        Schema::create('pre_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_order_id')->constrained('pre_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('price')->default(0);
            $table->integer('max_quantity_per_order')->default(10);
            $table->timestamps();
        });

        // 6. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 64)->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('order_type')->default('REGULAR'); // REGULAR, PRE_ORDER
            $table->foreignId('pre_order_id')->nullable()->constrained('pre_orders')->nullOnDelete();
            $table->timestamp('order_date')->useCurrent();
            $table->string('pickup_at')->nullable();
            $table->integer('subtotal')->default(0);
            $table->integer('discount_amount')->default(0);
            $table->integer('total_amount')->default(0);
            $table->string('status')->default('PENDING'); // PENDING, CONFIRMED, PREPARING, READY_FOR_PICKUP, COMPLETED, CANCELLED
            $table->text('customer_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index('order_code');
            $table->index('status');
            $table->index('order_date');
        });

        // 7. Order Items (Snapshot)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name_snapshot');
            $table->string('sku_snapshot')->nullable();
            $table->integer('unit_price')->default(0);
            $table->integer('quantity')->default(1);
            $table->integer('subtotal')->default(0);
            $table->timestamps();
        });

        // 8. Order Status Histories
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 9. Inventory Movements
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('movement_type'); // IN, OUT, ADJUSTMENT, SALE, RETURN
            $table->integer('quantity_change');
            $table->string('reference_type')->nullable(); // Order, Manual, StockOpname
            $table->string('reference_id')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });

        // 10. Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('pre_order_products');
        Schema::dropIfExists('pre_orders');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
