<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            [
                'key' => 'INSTAGRAM',
                'label' => 'Instagram',
                'value' => 'https://instagram.com/olay.az',
                'type' => 'url',
                'description' => 'Ссылка на Instagram профиль'
            ],
            [
                'key' => 'FACEBOOK',
                'label' => 'Facebook',
                'value' => 'https://facebook.com/olay.az',
                'type' => 'url',
                'description' => 'Ссылка на Facebook страницу'
            ],
            [
                'key' => 'YOUTUBE',
                'label' => 'YouTube',
                'value' => 'https://youtube.com/@olay.az',
                'type' => 'url',
                'description' => 'Ссылка на YouTube канал'
            ],
            [
                'key' => 'TIKTOK',
                'label' => 'TikTok',
                'value' => 'https://tiktok.com/@olay.az',
                'type' => 'url',
                'description' => 'Ссылка на TikTok профиль'
            ],
            [
                'key' => 'TELEGRAM',
                'label' => 'Telegram',
                'value' => 'https://t.me/olay_az',
                'type' => 'url',
                'description' => 'Ссылка на Telegram канал'
            ],
            [
                'key' => 'PHONE',
                'label' => 'Телефон',
                'value' => '+994',
                'type' => 'phone',
                'description' => 'Контактный телефон'
            ],
            [
                'key' => 'TWITTER',
                'label' => 'Twitter (X)',
                'value' => 'https://twitter.com/olay_az',
                'type' => 'url',
                'description' => 'Ссылка на Twitter профиль'
            ],
            [
                'key' => 'WHATSAPP',
                'label' => 'WhatsApp',
                'value' => '+994',
                'type' => 'phone',
                'description' => 'WhatsApp номер'
            ],
            [
                'key' => 'EMAIL_RECEIVE_MESSAGES',
                'label' => 'Email для сообщений',
                'value' => 'info@olay.az',
                'type' => 'email',
                'description' => 'Email для получения сообщений с сайта'
            ],
        ];

        foreach ($configs as $config) {
            Config::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }
    }
}
