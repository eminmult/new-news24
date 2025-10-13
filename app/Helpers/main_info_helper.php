<?php

use App\Models\MainInfo;

if (!function_exists('main_info')) {
    /**
     * Get the main info instance
     *
     * @return MainInfo|null
     */
    function main_info(): ?MainInfo
    {
        return MainInfo::getInstance();
    }
}

if (!function_exists('site_name')) {
    /**
     * Get the site name
     *
     * @return string
     */
    function site_name(): string
    {
        return main_info()?->site_name ?? 'OLAY.az';
    }
}

if (!function_exists('site_url')) {
    /**
     * Get the site URL
     *
     * @return string
     */
    function site_url(): string
    {
        return main_info()?->site_url ?? config('app.url');
    }
}
