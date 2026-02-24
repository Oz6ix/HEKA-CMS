<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SettingSiteLogo;
use App\Models\SettingsSiteGeneral;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Site Logo
        if (SettingSiteLogo::count() == 0) {
            SettingSiteLogo::create([
                'id' => 1,
                'logo_desktop' => 'logo.png',
                'logo_mobile' => 'logo-mobile.png',
                'favicon' => 'favicon.ico',
                'homepage_title' => 'HEKA Vibe',
                'homepage_keywords' => 'hospital, management, system',
                'homepage_description' => 'Hospital Management System',
                'footer_copy_right' => '© 2026 HEKA Vibe',
            ]);
        }

        // Seed General Settings
        if (SettingsSiteGeneral::count() == 0) {
            SettingsSiteGeneral::create([
                'id' => 1,
                'hospital_name' => 'HEKA Vibe',
                'hospital_code' => 'HEKA',
                'contact_email' => 'admin@hekavibe.com',
                'contact_phone' => '1234567890',
                'hospital_address' => '123 Health Street',
            ]);
        }
    }
}
