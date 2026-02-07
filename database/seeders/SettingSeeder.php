<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'system_name',
                'value' => 'SMA Muhammadiyah Kasihan',
                'type' => 'text',
            ],
            [
                'key' => 'system_title',
                'value' => 'Sistem Informasi Keuangan',
                'type' => 'text',
            ],
            // Images (using current defaults as placeholders if needed, but empty for now to fall back to hardcoded)
            [
                'key' => 'login_bg_image',
                'value' => null, // Will use default if null
                'type' => 'image',
            ],
            [
                'key' => 'system_logo',
                'value' => null,
                'type' => 'image',
            ],
            [
                'key' => 'favicon',
                'value' => null,
                'type' => 'image',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
