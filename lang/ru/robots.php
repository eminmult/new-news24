<?php

return [
    'navigation_label' => 'Robots.txt',
    'title' => 'Редактор Robots.txt',

    'sections' => [
        'editor' => 'Файл Robots.txt',
        'editor_description' => 'Настройте правила для поисковых роботов. Изменения применяются немедленно.',
    ],

    'fields' => [
        'content' => 'Содержимое',
        'content_helper' => 'Содержимое файла robots.txt. Будьте осторожны - неправильная конфигурация может повлиять на индексацию вашего сайта.',
    ],

    'actions' => [
        'save' => 'Сохранить',
    ],

    'notifications' => [
        'saved_title' => 'Сохранено',
        'saved_body' => 'Файл robots.txt успешно обновлен. Резервная копия создана.',
    ],
];
