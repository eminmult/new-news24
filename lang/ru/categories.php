<?php

return [
    'resource' => [
        'label' => 'Категория',
        'plural_label' => 'Категории',
        'navigation_label' => 'Категории',
        'navigation_group' => 'Контент',
    ],

    'fields' => [
        'name' => 'Название',
        'slug' => 'Slug',
        'color' => 'Цвет',
        'description' => 'Описание',
        'is_active' => 'Активна',
        'show_in_menu' => 'Показать в меню',
    ],

    'table' => [
        'columns' => [
            'name' => 'Название',
            'slug' => 'Slug',
            'color' => 'Цвет',
            'is_active' => 'Активна',
            'show_in_menu' => 'В меню',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ],
        'actions' => [
            'edit' => 'Редактировать',
            'delete' => 'Удалить',
        ],
    ],
];
