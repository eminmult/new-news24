<?php

return [
    'title' => 'İstifadəçilər',
    'singular' => 'İstifadəçi',
    'plural' => 'İstifadəçilər',

    'fields' => [
        'name' => 'Ad',
        'email' => 'Email',
        'password' => 'Şifrə',
        'password_helper' => 'Cari şifrəni saxlamaq üçün boş buraxın',
        'is_active' => 'Aktiv',
        'is_active_helper' => 'Qeyri-aktiv istifadəçilər admin panelinə daxil ola bilməz, lakin onların postları saytda qalır',
        'created_at' => 'Yaradılma tarixi',
        'updated_at' => 'Yenilənmə tarixi',
    ],

    'status' => [
        'active' => 'Aktiv',
        'inactive' => 'Qeyri-aktiv',
    ],

    'actions' => [
        'create' => 'İstifadəçi yarat',
        'edit' => 'İstifadəçini redaktə et',
        'delete' => 'İstifadəçini sil',
    ],

    'notifications' => [
        'created' => 'İstifadəçi uğurla yaradıldı',
        'updated' => 'İstifadəçi uğurla yeniləndi',
        'deleted' => 'İstifadəçi uğurla silindi',
    ],
];
