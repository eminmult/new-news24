<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class ContactPageSeeder extends Seeder
{
    public function run(): void
    {
        // Contact page
        StaticPage::updateOrCreate(
            ['slug' => 'elaqe'],
            [
                'title' => 'Bizimlə Əlaqə',
                'is_active' => true,
                'content' => [
                    'hero' => [
                        'title' => 'Bizimlə Əlaqə',
                        'subtitle' => 'Bizimle əlaqə saxlamaq üçün aşağıdakı məlumatlardan istifadə edə bilərsiniz',
                    ],
                    'contact_cards' => [
                        [
                            'icon' => 'location',
                            'title' => 'Ünvan',
                            'lines' => [
                                'Old Town Plaza',
                                'Bəşir Səfəroğlu küçəsi, 123',
                                'Bakı, Azərbaycan',
                            ],
                        ],
                        [
                            'icon' => 'phone',
                            'title' => 'Telefon',
                            'lines' => [
                                '+994 99 270 77 77',
                            ],
                            'link' => 'tel:+994992707777',
                        ],
                        [
                            'icon' => 'email',
                            'title' => 'Email',
                            'lines' => [
                                'info@olay.az',
                            ],
                            'link' => 'mailto:info@olay.az',
                        ],
                    ],
                    'social' => [
                        'title' => 'Sosial Şəbəkələr',
                        'subtitle' => 'Bizi sosial şəbəkələrdə izləyin',
                        'links' => [
                            ['platform' => 'Instagram', 'url' => 'https://www.instagram.com/olay.az_official/', 'class' => 'instagram'],
                            ['platform' => 'Facebook', 'url' => 'https://www.facebook.com/olayofficial', 'class' => 'facebook'],
                            ['platform' => 'YouTube', 'url' => 'https://www.youtube.com/channel/UCAorrSTGj8vBM4R9lIYfdXw', 'class' => 'youtube'],
                            ['platform' => 'TikTok', 'url' => 'https://www.tiktok.com/@olayazofficial', 'class' => 'tiktok'],
                            ['platform' => 'Telegram', 'url' => 'https://t.me/olayaz', 'class' => 'telegram'],
                        ],
                    ],
                    'hours' => [
                        'title' => 'İş Saatları',
                        'schedule' => [
                            ['day' => 'Bazar ertəsi - Cümə', 'time' => '09:00 - 18:00', 'active' => true],
                            ['day' => 'Şənbə', 'time' => '10:00 - 15:00', 'active' => true],
                            ['day' => 'Bazar', 'time' => 'Bağlı', 'active' => false],
                        ],
                    ],
                    'map' => [
                        'title' => 'Bizim Ünvan',
                        'embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3039.5285897866916!2d49.85279!3d40.37797!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDIyJzQwLjciTiA0OcKwNTEnMTAuMCJF!5e0!3m2!1sen!2saz!4v1234567890123!5m2!1sen!2saz',
                    ],
                ],
            ]
        );
    }
}
