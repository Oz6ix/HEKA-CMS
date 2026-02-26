<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
            'name' => 'Super Admin',
            'phone' => '1234567890',
            'password' => \Hash::make('password'),
            'status' => 1,
            'delete_status' => 0,
            'permission_status' => 1,
            'group_id' => 1, // Correct column name
        ]);
        
        // Ensure default User Group exists
        if (\App\Models\UserGroup::count() == 0) {
            \App\Models\UserGroup::create([
                 'title' => 'Super Admin', 
                 'status' => 1,
                 'delete_status' => 0,
                 'admin_users' => 1,
                 'staff' => 1,
                 'patients' => 1,
                 'appointments' => 1,
                 'bills' => 1,
                 'inventory' => 1,
                 'appointment_report' => 1,
                 'revenue_report' => 1,
                 'general_settings' => 1,
                 'user_groups' => 1,
                 'notifications' => 1,
                 'hospital_charges' => 1,
                 'pharmacy' => 1,
                 'phatology' => 1,
                 'radiology' => 1,
                 'suppliers' => 1,
                 'configuration' => 1,
             ]);
        }
        
        // Check if SettingsSiteGeneral exists and create default if not (needed for Patient Creation)
        if (\App\Models\SettingsSiteGeneral::count() == 0) {
            \App\Models\SettingsSiteGeneral::create([
                'hospital_name' => 'HEKA Vibe',
                'hospital_code' => 'HEKA',
                'contact_email' => 'admin@hekavibe.com',
                'contact_phone' => '0000000000',
            ]);
        }

        $this->call([
            QASeeder::class,
            BloodGroupSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
