<?php

return [
    'resource' => [
        'label' => 'Müəllif',
        'plural_label' => 'Müəlliflər',
        'navigation_label' => 'Müəlliflər',
        'navigation_group' => 'Məzmun',
    ],

    'fields' => [
        'name' => 'Ad',
        'slug' => 'Slug',
        'avatar' => 'Müəllifin fotosu',
        'avatar_helper' => 'WebP formatında avtomatik olaraq 150x150 miniatür yaradılacaq',
        'bio' => 'Bioqrafiya',
    ],

    'table' => [
        'columns' => [
            'avatar' => 'Foto',
            'name' => 'Ad',
            'slug' => 'Slug',
            'posts_count' => 'Postlar',
            'created_at' => 'Yaradılıb',
            'updated_at' => 'Yenilənib',
        ],
        'actions' => [
            'edit' => 'Redaktə et',
            'delete' => 'Sil',
        ],
    ],
];
