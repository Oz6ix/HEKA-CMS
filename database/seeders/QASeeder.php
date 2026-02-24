<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PharmacyCategory;
use App\Models\Pharmacy;
use App\Models\PathologyCategory;
use App\Models\Pathology;
use App\Models\RadiologyCategory;
use App\Models\Radiology;
use App\Models\SymptomType;
use App\Models\Casualty;
use App\Models\Tpa;
use App\Models\Frequency;
use App\Models\Units;
use App\Models\UserGroup;
use App\Models\StaffDepartment;
use App\Models\StaffDesignation;
use App\Models\StaffRole;
use App\Models\StaffSpecialist;
use App\Models\BloodGroup;

class QASeeder extends Seeder
{
    public function run()
    {
        // Pharmacy
        $pCat = PharmacyCategory::firstOrCreate(['name' => 'General Medicine'], ['status' => 1, 'parent_id' => 0]);
        Pharmacy::firstOrCreate(['code' => 'P001'], [
            'pharmacy_category_id' => $pCat->id,
            'title' => 'Paracetamol',
            'company_name' => 'ABC Pharma',
            'unit' => 'Strip',
            'quantity' => 100,
            'price' => 1000,
            'status' => 1,
            'photo' => 'default.png',
            'delete_status' => 0
        ]);

        // Pathology
        $pathCat = PathologyCategory::firstOrCreate(['name' => 'Blood Test'], ['status' => 1, 'parent_id' => 0]);
        Pathology::firstOrCreate(['code' => 'FBS'], [
            'pathology_category_id' => $pathCat->id,
            'test' => 'Fasting Blood Sugar',
            'charge' => 5000,
            'report_days' => 1,
            'status' => 1,
            'delete_status' => 0
        ]);

        // Radiology
        $radCat = RadiologyCategory::firstOrCreate(['name' => 'X-Ray'], ['status' => 1, 'parent_id' => 0]);
        Radiology::firstOrCreate(['code' => 'CXR'], [
            'radiology_category_id' => $radCat->id,
            'test' => 'Chest X-Ray',
            'charge' => 15000,
            'report_days' => 1,
            'status' => 1,
            'delete_status' => 0
        ]);
        
        // Ensure others exist too (idempotent)
        SymptomType::firstOrCreate(['symptom' => 'Fever'], ['status' => 1, 'delete_status' => 0]);
        Casualty::firstOrCreate(['casualty' => 'Accident'], ['status' => 1, 'delete_status' => 0]);
        Tpa::firstOrCreate(['tpa' => 'Insurance A'], ['status' => 1, 'delete_status' => 0]);
        Frequency::firstOrCreate(['frequency' => 'Daily'], ['status' => 1, 'delete_status' => 0]);
        Units::firstOrCreate(['unit' => 'mg'], ['status' => 1, 'delete_status' => 0]);
        
        // Staff/Patient stuff
        $dept = StaffDepartment::firstOrCreate(['department' => 'General'], ['status' => 1, 'delete_status' => 0]);
        $desig = StaffDesignation::firstOrCreate(['designation' => 'Doctor'], ['status' => 1, 'delete_status' => 0]);
        $role = StaffRole::firstOrCreate(['role' => 'Doctor'], ['status' => 1, 'delete_status' => 0]);
        $spec = StaffSpecialist::firstOrCreate(['specialist' => 'General Practitioner'], ['status' => 1, 'delete_status' => 0]);
        BloodGroup::firstOrCreate(['blood_group' => 'A+'], ['status' => 1, 'delete_status' => 0]);
        
        $doctorGroup = UserGroup::firstOrCreate(['title' => 'Doctor'], ['status' => 1, 'delete_status' => 0]);
        UserGroup::firstOrCreate(['title' => 'Staff'], ['status' => 1, 'delete_status' => 0]);

        // Create Dummy Staff Doctor
        $staff = \App\Models\Staff::firstOrCreate(
            ['email' => 'doctor@hekavibe.com'],
            [
                'staff_code' => 'DOC001',
                'hospital_id' => 1,
                'designation_id' => $desig->id,
                'department_id' => $dept->id,
                'role_id' => $role->id,
                'specialist_id' => $spec->id,
                'name' => 'Dr. House',
                'phone' => '1231231234',
                'current_address' => 'Princeton-Plainsboro',
                'qualification' => 'MD',
                'work_experience' => '20 Years',
                'status' => 1,
                'delete_status' => 0,
            ]
        );

        // Create Dummy Doctor User
        \App\Models\User::firstOrCreate(
            ['email' => 'doctor@hekavibe.com'],
            [
                'name' => 'Dr. House',
                'phone' => '1231231234',
                'password' => \Hash::make('password'),
                'status' => 1,
                'delete_status' => 0,
                'permission_status' => 1,
                'group_id' => $doctorGroup->id,
                'staff_id' => $staff->id,
            ]
        );

        // Create Dummy Patient
        $hospital_code = 'HEKA';
        \App\Models\Patient::firstOrCreate(
            ['email' => 'patient@hekavibe.com'],
            [
                'patient_code' => $hospital_code . '00001',
                'patient_folder_name' => $hospital_code . '00001_John',
                'name' => 'John Doe',
                'phone' => '0987654321',
                'password' => \Hash::make('password'),
                'dob' => '1990-01-01',
                'dob_str' => strtotime('1990-01-01'),
                'gender' => 1,
                'guardian_name' => 'Jane Doe',
                'blood_group' => 1,
                'status' => 1,
                'delete_status' => 0,
                'age_year' => 34,
                'age_month' => 0,
            ]
        );
    }
}
