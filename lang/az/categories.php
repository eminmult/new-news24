<?php

return [
    'resource' => [
        'label' => 'Kateqoriya',
        'plural_label' => 'Kateqoriyalar',
        'navigation_label' => 'Kateqoriyalar',
        'navigation_group' => 'Məzmun',
    ],

    'fields' => [
        'name' => 'Ad',
        'slug' => 'Slug',
        'color' => 'Rəng',
        'description' => 'Təsvir',
        'is_active' => 'Aktiv',
        'show_in_menu' => 'Menyuda göstər',
    ],

    'table' => [
        'columns' => [
            'name' => 'Ad',
            'slug' => 'Slug',
            'color' => 'Rəng',
            'is_active' => 'Aktiv',
            'show_in_menu' => 'Menyuda',
            'created_at' => 'Yaradılıb',
            'updated_at' => 'Yenilənib',
        ],
        'actions' => [
            'edit' => 'Redaktə et',
            'delete' => 'Sil',
        ],
    ],
];
