<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Zeev Jewelry'],
            ['key' => 'currency', 'value' => 'USD'],
            ['key' => 'theme_options', 'value' => json_encode(['light', 'dark'])],
            ['key' => 'default_language', 'value' => 'en'],
            ['key' => 'default_theme', 'value' => 'dark'], // Set the default theme to dark
        ];

        foreach ($settings as $setting) {
            Settings::create($setting);
        }
    }
}
