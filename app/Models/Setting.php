<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, ?string $value, bool $isPublic = false): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'is_public' => $isPublic]
        );
    }
}
