<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'customer_id',
        'order_type',
        'pre_order_id',
        'order_date',
        'pickup_at',
        'subtotal',
        'discount_amount',
        'total_amount',
        'status',
        'customer_notes',
        'internal_notes',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'subtotal' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function preOrder(): BelongsTo
    {
        return $this->belongsTo(PreOrder::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
