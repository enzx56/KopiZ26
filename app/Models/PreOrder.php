<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreOrder extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'banner_path',
        'starts_at',
        'ends_at',
        'pickup_at',
        'quota_units',
        'reserved_units',
        'status',
        'instruction_notes',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'pickup_at' => 'datetime',
        'published_at' => 'datetime',
        'quota_units' => 'integer',
        'reserved_units' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'pre_order_products')
            ->withPivot(['price', 'max_quantity_per_order'])
            ->withTimestamps();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isOpen(): bool
    {
        if ($this->status !== 'OPEN') {
            return false;
        }

        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return $this->reserved_units < $this->quota_units;
    }

    public function remainingQuota(): int
    {
        return max(0, $this->quota_units - $this->reserved_units);
    }
}
