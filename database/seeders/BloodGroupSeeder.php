<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\BloodGroup;

class BloodGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bloodGroups = [
            ['blood_group' => 'A+', 'status' => 1, 'delete_status' => 0],
            ['blood_group' => 'A-', 'status' => 1, 'delete_status' => 0],
            ['blood_group' => 'B+', 'status' => 1, 'delete_status' => 0],
            ['blood_group' => 'B-', 'status' => 1, 'delete_status' => 0],
            ['blood_group' => 'O+', 'status' => 1, 'delete_status' => 0],
            ['blood_group' => 'O-', 'status' => 1, 'delete_status' => 0],
            ['blood_group' => 'AB+', 'status' => 1, 'delete_status' => 0],
            ['blood_group' => 'AB-', 'status' => 1, 'delete_status' => 0],
        ];

        foreach ($bloodGroups as $group) {
            BloodGroup::firstOrCreate(['blood_group' => $group['blood_group']], $group);
        }
    }
}
