<?php

use App\Models\Config;

if (!function_exists('config_value')) {
    /**
     * Get configuration value by key
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    function config_value(string $key, ?string $default = null): ?string
    {
        return Config::getValue($key, $default);
    }
}
