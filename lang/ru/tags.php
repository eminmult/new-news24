<?php

return [
    'resource' => [
        'label' => 'Тег',
        'plural_label' => 'Теги',
        'navigation_label' => 'Теги',
        'navigation_group' => 'Контент',
    ],

    'fields' => [
        'name' => 'Название',
        'slug' => 'Slug',
    ],

    'table' => [
        'columns' => [
            'name' => 'Название',
            'slug' => 'Slug',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ],
        'actions' => [
            'edit' => 'Редактировать',
            'delete' => 'Удалить',
        ],
    ],
];
