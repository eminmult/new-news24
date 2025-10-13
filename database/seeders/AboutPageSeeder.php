<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        // About page
        StaticPage::updateOrCreate(
            ['slug' => 'haqqimizda'],
            [
                'title' => 'Haqqımızda',
                'is_active' => true,
                'content' => [
                    'hero' => [
                        'title' => 'OLAY.AZ - Olay Olacaq!',
                        'subtitle' => 'Azərbaycanın əyləncə və mədəniyyət portalı',
                    ],
                    'story' => [
                        'title' => 'Haqqımızda',
                        'lead' => 'OLAY.AZ - 4 oktyabr 2021-ci ildə "Liatris Holding" şirkəti tərəfindən yaradılmış onlayn qəzetdir.',
                        'paragraphs' => [
                            ['text' => '14 noyabr 2022-ci ildən etibarən isə portal müstəqil fəaliyyətə başladı. Biz Azərbaycanda baş verən mədəni hadisələr, maraqlı dünya xəbərləri, əyləncə, mədəniyyət, moda və gözəllik mövzularında məlumatlar təqdim edirik.'],
                            ['text' => 'Portalımız obyektiv və müstəqil xəbərləri şərh olmadan çatdırmağı hədəfləyir. Müsahibələr, reportajlar və bloq yazıları ilə oxucularımıza zəngin məzmun təqdim edirik.'],
                        ],
                    ],
                    'mission' => [
                        'title' => 'Məqsədimiz',
                        'description' => 'Azərbaycanın mədəni yeniliklərini, əyləncə dünyasının ən maraqlı xəbərlərini və həyat tərzi mövzularını peşəkar şəkildə işıqlandırmaqdır.',
                        'cards' => [
                            ['title' => 'Obyektivlik', 'text' => 'Şərh olmadan, obyektiv və müstəqil xəbərlər çatdırırıq'],
                            ['title' => 'Keyfiyyət', 'text' => 'Yüksək keyfiyyətli məzmun və peşəkar yanaşma'],
                            ['title' => 'Yenilik', 'text' => 'Ən son mədəni yeniliklər və trendlər haqqında'],
                        ],
                    ],
                    'stats' => [
                        ['value' => 3, 'label' => 'İllik Təcrübə'],
                        ['value' => 5000, 'label' => 'Yayımlanmış Xəbər'],
                        ['value' => 1200000, 'label' => 'Aylıq Oxucu'],
                        ['value' => 50, 'label' => 'Mütəmadi Müsahibələr'],
                    ],
                    'team' => [
                        'title' => 'Komandamız',
                        'description' => 'Peşəkar və təcrübəli komandamız ilə oxucularımıza ən yaxşı məzmunu təqdim edirik',
                        'members' => [
                            ['name' => 'Ruhiyyə Əliyeva', 'position' => 'Təsisçi', 'photo' => null, 'social_instagram' => null],
                            ['name' => 'Pərviz Hüseyn', 'position' => 'Baş Redaktor', 'photo' => null, 'social_instagram' => null],
                        ],
                    ],
                    'timeline' => [
                        'title' => 'Bizim Tariximiz',
                        'events' => [
                            ['date' => '4 Oktyabr 2021', 'title' => 'Başlanğıc', 'text' => 'OLAY.AZ "Liatris Holding" şirkəti tərəfindən yaradıldı'],
                            ['date' => '14 Noyabr 2022', 'title' => 'Müstəqillik', 'text' => 'Portal müstəqil fəaliyyətə başladı'],
                            ['date' => '2023-2024', 'title' => 'Böyümə', 'text' => 'Oxucu auditoriyası artdı, yeni formatlar və layihələr həyata keçirildi'],
                            ['date' => '2025', 'title' => 'Gələcək', 'text' => 'Yeni dizayn, genişlənmiş məzmun və daha çox yeniliklər'],
                        ],
                    ],
                ],
            ]
        );
    }
}
