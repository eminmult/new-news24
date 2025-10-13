<?php

return [
    'resource' => [
        'label' => 'Teq',
        'plural_label' => 'Teqlər',
        'navigation_label' => 'Teqlər',
        'navigation_group' => 'Məzmun',
    ],

    'fields' => [
        'name' => 'Ad',
        'slug' => 'Slug',
    ],

    'table' => [
        'columns' => [
            'name' => 'Ad',
            'slug' => 'Slug',
            'created_at' => 'Yaradılıb',
            'updated_at' => 'Yenilənib',
        ],
        'actions' => [
            'edit' => 'Redaktə et',
            'delete' => 'Sil',
        ],
    ],
];
