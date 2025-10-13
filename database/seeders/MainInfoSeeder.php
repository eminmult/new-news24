<?php

namespace Database\Seeders;

use App\Models\MainInfo;
use Illuminate\Database\Seeder;

class MainInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MainInfo::create([
            'site_name' => 'OLAY.az',
            'site_url' => 'https://olay.az',
            'site_title' => 'OLAY.az - Azərbaycanın xəbər portalı',
            'site_description' => 'Azərbaycanda və dünyada baş verən ən son xəbərlər, hadisələr və təhlillər',
            'address' => 'Bakı, Azərbaycan',
            'emails' => ['info@olay.az'],
            'phones' => ['+994'],
            'fax' => null,
            'location' => null,
            'reklam_phones' => ['+994'],
            'reklam_emails' => ['reklam@olay.az'],
            'meta_title' => 'OLAY.az - Azərbaycanın xəbər portalı',
            'meta_description' => 'Azərbaycanda və dünyada baş verən ən son xəbərlər, hadisələr və təhlillər',
            'meta_keywords' => 'xəbər, Azərbaycan, olay, hadisələr, son xəbərlər',
            'logo' => null,
            'favicon' => null,
        ]);
    }
}
