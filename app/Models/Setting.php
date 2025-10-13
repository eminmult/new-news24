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
     * Get setting value by name
     */
    public static function get(string $name, mixed $default = null): mixed
    {
        $setting = self::getByName($name);
        return $setting ? $setting->getValue() : $default;
    }

    /**
     * Set setting value by name
     */
    public static function set(string $name, mixed $value, string $type = 'string'): void
    {
        $setting = self::firstOrCreate(['name' => $name], ['type' => $type]);
        $setting->setValue($value);
        $setting->save();
    }
}
