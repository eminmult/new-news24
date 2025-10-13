<?php

return [
    'navigation_label' => 'Основная информация',
    'title' => 'Основная информация сайта',

    'sections' => [
        'main' => 'Основная информация',
        'contact' => 'Контактная информация',
        'advertising' => 'Рекламная информация',
        'seo' => 'SEO и Meta информация',
    ],

    'fields' => [
        'site_name' => 'Название сайта',
        'site_url' => 'URL сайта',
        'site_title' => 'Заголовок сайта',
        'site_description' => 'Описание сайта',
        'address' => 'Адрес',
        'emails' => 'Email адреса',
        'phones' => 'Телефоны',
        'fax' => 'Факс',
        'location' => 'Локация (Google Maps)',
        'reklam_emails' => 'Email для рекламы',
        'reklam_phones' => 'Телефоны для рекламы',
        'meta_title' => 'Meta Title',
        'meta_description' => 'Meta Description',
        'meta_keywords' => 'Meta Keywords',
        'meta_keywords_helper' => 'Ключевые слова через запятую',
    ],

    'actions' => [
        'save' => 'Сохранить',
        'add_email' => 'Добавить email',
        'add_phone' => 'Добавить телефон',
    ],

    'notifications' => [
        'saved_title' => 'Сохранено',
        'saved_body' => 'Основная информация успешно обновлена.',
    ],
];
