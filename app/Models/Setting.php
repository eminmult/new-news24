<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'name',
        'value',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get setting value with proper type casting
     */
    public function getValue(): mixed
    {
        return match ($this->type) {
            'numeric' => (int) $this->value,
            'json' => $this->value ? json_decode($this->value, true) : [],
            default => $this->value,
        };
    }

    /**
     * Set setting value
     */
    public function setValue(mixed $value): void
    {
        $this->value = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get setting by name
     */
    public static function getByName(string $name): ?self
    {
        return self::where('name', $name)->first();
    }

    /**
     * Get setting value by name (with 1 year cache)
     */
    public static function get(string $name, mixed $default = null): mixed
    {
        return \Illuminate\Support\Facades\Cache::remember("setting_{$name}", 31536000, function() use ($name, $default) {
            $setting = self::getByName($name);
            return $setting ? $setting->getValue() : $default;
        });
    }

    /**
     * Set setting value by name
     */
    public static function set(string $name, mixed $value, string $type = 'string'): void
    {
        $setting = self::firstOrCreate(['name' => $name], ['type' => $type]);
        $setting->setValue($value);
        $setting->save();

        // Очищаем кеш настройки
        \Illuminate\Support\Facades\Cache::forget("setting_{$name}");
    }

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        static::saved(function ($setting) {
            \Illuminate\Support\Facades\Cache::forget("setting_{$setting->name}");
        });

        static::deleted(function ($setting) {
            \Illuminate\Support\Facades\Cache::forget("setting_{$setting->name}");
        });
    }
}
