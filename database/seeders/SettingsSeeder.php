<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'name' => 'chosen_tags_count',
                'value' => '8',
                'type' => 'numeric',
            ],
            [
                'name' => 'home_posts_count',
                'value' => '15',
                'type' => 'numeric',
            ],
            [
                'name' => 'search_posts_count',
                'value' => '12',
                'type' => 'numeric',
            ],
            [
                'name' => 'category_posts_count',
                'value' => '12',
                'type' => 'numeric',
            ],
            [
                'name' => 'tag_posts_count',
                'value' => '12',
                'type' => 'numeric',
            ],
            [
                'name' => 'slider_posts_count',
                'value' => '5',
                'type' => 'numeric',
            ],
            [
                'name' => 'related_posts_count',
                'value' => '6',
                'type' => 'numeric',
            ],
            [
                'name' => 'trending_posts_count',
                'value' => '5',
                'type' => 'numeric',
            ],
            [
                'name' => 'chosen_tags',
                'value' => json_encode([]),
                'type' => 'json',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['name' => $setting['name']],
                $setting
            );
        }
    }
}
