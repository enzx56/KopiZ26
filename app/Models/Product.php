<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'slug',
        'short_description',
        'description',
        'image_path',
        'price',
        'status',
        'track_inventory',
        'stock_quantity',
        'is_featured',
        'is_active',
        'archived_at',
    ];

    protected $casts = [
        'price' => 'integer',
        'track_inventory' => 'boolean',
        'stock_quantity' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function preOrders(): BelongsToMany
    {
        return $this->belongsToMany(PreOrder::class, 'pre_order_products')
            ->withPivot(['price', 'max_quantity_per_order'])
            ->withTimestamps();
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('archived_at');
    }

    public function scopeAvailableForSale($query)
    {
        return $query->active()->whereIn('status', ['READY', 'PO']);
    }
}
