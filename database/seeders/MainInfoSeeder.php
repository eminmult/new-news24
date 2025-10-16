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
            'site_name' => 'News24.az',
            'site_url' => 'https://news24.az',
            'site_title' => 'News24.az - Azərbaycanın xəbər portalı',
            'site_description' => 'Azərbaycanda və dünyada baş verən ən son xəbərlər, hadisələr və təhlillər',
            'address' => 'Bakı, Azərbaycan',
            'emails' => ['info@news24.az'],
            'phones' => ['+994'],
            'fax' => null,
            'location' => null,
            'reklam_phones' => ['+994'],
            'reklam_emails' => ['reklam@news24.az'],
            'meta_title' => 'News24.az - Azərbaycanın xəbər portalı',
            'meta_description' => 'Azərbaycanda və dünyada baş verən ən son xəbərlər, hadisələr və təhlillər',
            'meta_keywords' => 'xəbər, Azərbaycan, news24, hadisələr, son xəbərlər',
            'logo' => null,
            'favicon' => null,
        ]);
    }
}
