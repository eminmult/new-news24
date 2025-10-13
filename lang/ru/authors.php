<?php

return [
    'resource' => [
        'label' => 'Автор',
        'plural_label' => 'Авторы',
        'navigation_label' => 'Авторы',
        'navigation_group' => 'Контент',
    ],

    'fields' => [
        'name' => 'Имя',
        'slug' => 'Slug',
        'avatar' => 'Фото автора',
        'avatar_helper' => 'Будет автоматически создана миниатюра 150x150 в формате WebP',
        'bio' => 'Биография',
    ],

    'table' => [
        'columns' => [
            'avatar' => 'Фото',
            'name' => 'Имя',
            'slug' => 'Slug',
            'posts_count' => 'Постов',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ],
        'actions' => [
            'edit' => 'Редактировать',
            'delete' => 'Удалить',
        ],
    ],
];
