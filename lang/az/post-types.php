<?php

return [
    'resource' => [
        'label' => 'Post növü',
        'plural_label' => 'Post növləri',
        'navigation_label' => 'Post növləri',
        'navigation_group' => 'Məzmun',
    ],

    'sections' => [
        'basic_info' => 'Əsas məlumat',
        'settings' => 'Parametrlər',
    ],

    'fields' => [
        'name' => 'Ad',
        'slug' => 'Slug',
        'icon' => 'İkona (emoji)',
        'icon_helper' => 'Məsələn: 📝, 📷, 🎥',
        'color' => 'Rəng',
        'is_active' => 'Aktiv',
        'order' => 'Sıralama',
        'order_helper' => 'Nə qədər kiçik rəqəm, bir o qədər yuxarıda',
    ],

    'table' => [
        'columns' => [
            'icon' => 'İkona',
            'name' => 'Ad',
            'slug' => 'Slug',
            'color' => 'Rəng',
            'is_active' => 'Aktiv',
            'order' => 'Sıra',
            'posts_count' => 'Post sayı',
        ],
    ],
];
