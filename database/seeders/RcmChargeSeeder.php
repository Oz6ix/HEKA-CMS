<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RcmChargeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Charge Categories
        $categories = [
            ['name' => 'Consultation Fee',         'description' => 'Doctor consultation and follow-up fees',  'parent_id' => 0],
            ['name' => 'Pharmacy',                  'description' => 'Medication and drug dispensing',           'parent_id' => 0],
            ['name' => 'Laboratory (Pathology)',    'description' => 'Lab tests and pathology services',         'parent_id' => 0],
            ['name' => 'Radiology / Imaging',       'description' => 'X-ray, ultrasound, CT, MRI',              'parent_id' => 0],
            ['name' => 'Procedure / Surgery',       'description' => 'Minor/major procedures and surgeries',    'parent_id' => 0],
            ['name' => 'Consumables',               'description' => 'Medical consumables used during visit',   'parent_id' => 0],
            ['name' => 'Other Services',            'description' => 'Miscellaneous hospital services',         'parent_id' => 0],
        ];

        foreach ($categories as $cat) {
            DB::table('hospital_settings_hospital_charge_category')->updateOrInsert(
                ['name' => $cat['name']],
                array_merge($cat, ['status' => 1, 'delete_status' => 0, 'created_at' => $now, 'updated_at' => $now])
            );
        }

        // Get category IDs
        $catIds = DB::table('hospital_settings_hospital_charge_category')
            ->whereIn('name', array_column($categories, 'name'))
            ->pluck('id', 'name');

        // Standard Charges per Category
        $charges = [
            // Consultation
            ['cat' => 'Consultation Fee', 'code' => 'CONS-GEN', 'title' => 'General Consultation',      'standard_charge' => 5000],
            ['cat' => 'Consultation Fee', 'code' => 'CONS-FUP', 'title' => 'Follow-up Consultation',    'standard_charge' => 3000],
            ['cat' => 'Consultation Fee', 'code' => 'CONS-SPL', 'title' => 'Specialist Consultation',   'standard_charge' => 10000],
            // Procedure
            ['cat' => 'Procedure / Surgery', 'code' => 'PROC-MIN', 'title' => 'Minor Procedure',        'standard_charge' => 15000],
            ['cat' => 'Procedure / Surgery', 'code' => 'PROC-MAJ', 'title' => 'Major Procedure',        'standard_charge' => 50000],
            ['cat' => 'Procedure / Surgery', 'code' => 'PROC-DRE', 'title' => 'Wound Dressing',         'standard_charge' => 2000],
            // Other
            ['cat' => 'Other Services', 'code' => 'OTH-ADM', 'title' => 'Administrative Fee',           'standard_charge' => 1000],
            ['cat' => 'Other Services', 'code' => 'OTH-AMB', 'title' => 'Ambulance Service',            'standard_charge' => 20000],
            ['cat' => 'Other Services', 'code' => 'OTH-BED', 'title' => 'Bed Charge (per day)',         'standard_charge' => 5000],
        ];

        foreach ($charges as $charge) {
            DB::table('hospital_settings_hospital_charges')->updateOrInsert(
                ['code' => $charge['code']],
                [
                    'hospital_charge_category_id' => $catIds[$charge['cat']] ?? 0,
                    'code' => $charge['code'],
                    'title' => $charge['title'],
                    'standard_charge' => $charge['standard_charge'],
                    'description' => '',
                    'status' => 1,
                    'delete_status' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
