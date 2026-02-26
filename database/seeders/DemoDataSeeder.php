<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🏥 Seeding HEKA Demo Data...');

        // ── REFERENCE DATA ──────────────────────────────────────
        $bloodGroups = [];
        foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg) {
            $bloodGroups[] = \App\Models\BloodGroup::firstOrCreate(['blood_group' => $bg], ['status' => 1, 'delete_status' => 0])->id;
        }

        $departments = [];
        foreach (['General Medicine','Pediatrics','Cardiology','Orthopedics','Dermatology','Ophthalmology','ENT','Neurology','Gynecology','Surgery'] as $d) {
            $departments[] = \App\Models\StaffDepartment::firstOrCreate(['department' => $d], ['status' => 1, 'delete_status' => 0])->id;
        }

        $designations = [];
        foreach (['Senior Consultant','Consultant','Registrar','Resident','Medical Officer'] as $d) {
            $designations[] = \App\Models\StaffDesignation::firstOrCreate(['designation' => $d], ['status' => 1, 'delete_status' => 0])->id;
        }

        $roles = [];
        foreach (['Doctor','Nurse','Pharmacist','Lab Technician','Receptionist'] as $r) {
            $roles[] = \App\Models\StaffRole::firstOrCreate(['role' => $r], ['status' => 1, 'delete_status' => 0])->id;
        }

        $specialists = [];
        foreach (['Cardiologist','Orthopedic Surgeon','Pediatrician','Dermatologist','General Practitioner','Neurologist','Gynecologist','Ophthalmologist','ENT Specialist','Surgeon'] as $s) {
            $specialists[] = \App\Models\StaffSpecialist::firstOrCreate(['specialist' => $s], ['status' => 1, 'delete_status' => 0])->id;
        }

        // Pharmacy categories
        $pharmCats = [];
        foreach (['Antibiotics','Painkillers','Cardiovascular','Antipyretics','Antacids','Vitamins & Supplements','Dermatologicals','Respiratory','Anti-Diabetics','Gastrointestinal'] as $c) {
            $pharmCats[] = \App\Models\PharmacyCategory::firstOrCreate(['name' => $c], ['status' => 1, 'parent_id' => 0])->id;
        }

        // Pathology categories
        $pathCats = [];
        foreach (['Blood Test','Urine Test','Liver Function','Kidney Function','Thyroid Panel'] as $c) {
            $pathCats[] = \App\Models\PathologyCategory::firstOrCreate(['name' => $c], ['status' => 1, 'parent_id' => 0])->id;
        }

        // Radiology categories
        $radCats = [];
        foreach (['X-Ray','Ultrasound','CT Scan','MRI','ECG'] as $c) {
            $radCats[] = \App\Models\RadiologyCategory::firstOrCreate(['name' => $c], ['status' => 1, 'parent_id' => 0])->id;
        }

        // Symptom Types
        foreach (['Fever','Headache','Chest Pain','Cough','Abdominal Pain','Dizziness','Fatigue','Joint Pain','Skin Rash','Shortness of Breath'] as $s) {
            \App\Models\SymptomType::firstOrCreate(['symptom' => $s], ['status' => 1, 'delete_status' => 0]);
        }
        $symptomIds = \App\Models\SymptomType::pluck('id')->toArray();

        // Casualty Types
        foreach (['Accident','Emergency','Walk-in','Ambulance','Police Case'] as $c) {
            \App\Models\Casualty::firstOrCreate(['casualty' => $c], ['status' => 1, 'delete_status' => 0]);
        }
        $casualtyIds = \App\Models\Casualty::pluck('id')->toArray();

        // TPA
        foreach (['National Health Insurance','Blue Shield Coverage','MedCare Plus','Premium Health Plan','Corporate Wellness'] as $t) {
            \App\Models\Tpa::firstOrCreate(['tpa' => $t], ['status' => 1, 'delete_status' => 0]);
        }

        // Frequencies & Units
        foreach (['Once Daily','Twice Daily','Three Times Daily','Four Times Daily','Every 6 Hours','Every 8 Hours','As Needed','Before Meals','After Meals','At Bedtime'] as $f) {
            \App\Models\Frequency::firstOrCreate(['frequency' => $f], ['status' => 1, 'delete_status' => 0]);
        }
        foreach (['mg','ml','g','mcg','IU','tablet','capsule','drops','puffs','units'] as $u) {
            \App\Models\Units::firstOrCreate(['unit' => $u], ['status' => 1, 'delete_status' => 0]);
        }

        // User Groups
        $doctorGroup = \App\Models\UserGroup::firstOrCreate(['title' => 'Doctor'], ['status' => 1, 'delete_status' => 0]);
        \App\Models\UserGroup::firstOrCreate(['title' => 'Nurse'], ['status' => 1, 'delete_status' => 0]);
        \App\Models\UserGroup::firstOrCreate(['title' => 'Lab Technician'], ['status' => 1, 'delete_status' => 0]);
        \App\Models\UserGroup::firstOrCreate(['title' => 'Pharmacist'], ['status' => 1, 'delete_status' => 0]);
        \App\Models\UserGroup::firstOrCreate(['title' => 'Receptionist'], ['status' => 1, 'delete_status' => 0]);

        // Hospital Charges (for treatments)
        $chargeIds = [];
        $charges = [
            ['code' => 'CONSULT', 'name' => 'General Consultation', 'charge' => 5000],
            ['code' => 'FOLLOW', 'name' => 'Follow-up Visit', 'charge' => 3000],
            ['code' => 'DRESS', 'name' => 'Wound Dressing', 'charge' => 2000],
            ['code' => 'INJECT', 'name' => 'Injection Administration', 'charge' => 1500],
            ['code' => 'MINOR', 'name' => 'Minor Surgery', 'charge' => 25000],
        ];
        foreach ($charges as $ch) {
            $chargeIds[] = \App\Models\HospitalCharge::firstOrCreate(
                ['charge_code' => $ch['code']],
                ['charge_name' => $ch['name'], 'charge' => $ch['charge'], 'status' => 1, 'delete_status' => 0, 'charge_category_id' => 0]
            )->id;
        }

        // Inventory categories
        $invCats = [];
        foreach (['Medical Equipment','Surgical Supplies','Consumables','PPE','Office Supplies'] as $c) {
            $invCats[] = \App\Models\InventoryCategory::firstOrCreate(['category_name' => $c], ['status' => 1, 'delete_status' => 0])->id;
        }

        // Suppliers
        $supplierIds = [];
        $suppliers = [
            ['name' => 'MedSupply Co.', 'contact' => '0912345001', 'email' => 'info@medsupply.com', 'address' => '123 Medical District, Singapore'],
            ['name' => 'PharmaTech Ltd.', 'contact' => '0912345002', 'email' => 'orders@pharmatech.com', 'address' => '456 Health Boulevard, Bangkok'],
            ['name' => 'SurgiCare International', 'contact' => '0912345003', 'email' => 'sales@surgicare.com', 'address' => '789 Hospital Road, Yangon'],
        ];
        foreach ($suppliers as $s) {
            $supplierIds[] = \App\Models\SettingsSupplier::firstOrCreate(
                ['email' => $s['email']],
                ['supplier_name' => $s['name'], 'contact_number' => $s['contact'], 'address' => $s['address'], 'status' => 1, 'delete_status' => 0]
            )->id;
        }

        $this->command->info('  ✓ Reference data seeded');

        // ── MEDICAL STAFF (10 doctors) ──────────────────────────
        $staffIds = [];
        $doctors = [
            ['name' => 'Dr. Emily Chen', 'email' => 'emily.chen@heka.com', 'phone' => '0911100001', 'qual' => 'MD, FRCP', 'exp' => '15 Years'],
            ['name' => 'Dr. James Wilson', 'email' => 'james.wilson@heka.com', 'phone' => '0911100002', 'qual' => 'MD, PhD', 'exp' => '12 Years'],
            ['name' => 'Dr. Aung Kyaw', 'email' => 'aung.kyaw@heka.com', 'phone' => '0911100003', 'qual' => 'MBBS, MS', 'exp' => '10 Years'],
            ['name' => 'Dr. Sarah Johnson', 'email' => 'sarah.johnson@heka.com', 'phone' => '0911100004', 'qual' => 'MD, Pediatrics', 'exp' => '8 Years'],
            ['name' => 'Dr. Michael Torres', 'email' => 'michael.torres@heka.com', 'phone' => '0911100005', 'qual' => 'MD, Cardiology', 'exp' => '20 Years'],
            ['name' => 'Dr. Nang Htwe', 'email' => 'nang.htwe@heka.com', 'phone' => '0911100006', 'qual' => 'MBBS, MD', 'exp' => '6 Years'],
            ['name' => 'Dr. Robert Park', 'email' => 'robert.park@heka.com', 'phone' => '0911100007', 'qual' => 'MD, Orthopedics', 'exp' => '14 Years'],
            ['name' => 'Dr. Aye Myat', 'email' => 'aye.myat@heka.com', 'phone' => '0911100008', 'qual' => 'MBBS, ENT', 'exp' => '9 Years'],
            ['name' => 'Dr. Lisa Wang', 'email' => 'lisa.wang@heka.com', 'phone' => '0911100009', 'qual' => 'MD, Dermatology', 'exp' => '7 Years'],
            ['name' => 'Dr. Zaw Min', 'email' => 'zaw.min@heka.com', 'phone' => '0911100010', 'qual' => 'MBBS, Surgery', 'exp' => '18 Years'],
        ];

        foreach ($doctors as $i => $doc) {
            $staff = \App\Models\Staff::firstOrCreate(
                ['email' => $doc['email']],
                [
                    'staff_code' => 'DOC' . str_pad($i + 2, 3, '0', STR_PAD_LEFT),
                    'hospital_id' => 1,
                    'designation_id' => $designations[$i % count($designations)],
                    'department_id' => $departments[$i % count($departments)],
                    'role_id' => $roles[0], // Doctor role
                    'specialist_id' => $specialists[$i % count($specialists)],
                    'name' => $doc['name'],
                    'phone' => $doc['phone'],
                    'current_address' => 'Yangon, Myanmar',
                    'qualification' => $doc['qual'],
                    'work_experience' => $doc['exp'],
                    'gender' => ($i % 2 == 0) ? 2 : 1,
                    'blood_group' => $bloodGroups[$i % count($bloodGroups)],
                    'status' => 1,
                    'delete_status' => 0,
                ]
            );
            $staffIds[] = $staff->id;
        }

        $this->command->info('  ✓ 10 Doctors seeded');

        // ── PATIENTS (12 patients) ──────────────────────────────
        $patientIds = [];
        $patients = [
            ['name' => 'Aung Aung', 'phone' => '0922200001', 'email' => 'aung@demo.com', 'dob' => '1985-03-15', 'gender' => 1, 'guardian' => 'U Maung'],
            ['name' => 'Ma Thin Thin', 'phone' => '0922200002', 'email' => 'thin@demo.com', 'dob' => '1990-07-22', 'gender' => 2, 'guardian' => 'Daw Khin'],
            ['name' => 'Ko Zaw Htoo', 'phone' => '0922200003', 'email' => 'zawhtoo@demo.com', 'dob' => '1978-11-08', 'gender' => 1, 'guardian' => 'U Htoo'],
            ['name' => 'Ma Hnin Si', 'phone' => '0922200004', 'email' => 'hninsi@demo.com', 'dob' => '2000-01-30', 'gender' => 2, 'guardian' => 'Daw Si'],
            ['name' => 'U Kyaw Soe', 'phone' => '0922200005', 'email' => 'kyawsoe@demo.com', 'dob' => '1965-05-12', 'gender' => 1, 'guardian' => 'Daw Mya'],
            ['name' => 'Ma Su Su', 'phone' => '0922200006', 'email' => 'susu@demo.com', 'dob' => '1995-09-18', 'gender' => 2, 'guardian' => 'U Win'],
            ['name' => 'Ko Ye Lin', 'phone' => '0922200007', 'email' => 'yelin@demo.com', 'dob' => '1988-12-25', 'gender' => 1, 'guardian' => 'U Lin'],
            ['name' => 'Daw Thin Myat', 'phone' => '0922200008', 'email' => 'thinmyat@demo.com', 'dob' => '1972-04-03', 'gender' => 2, 'guardian' => 'U Myat'],
            ['name' => 'Mg Htet Aung', 'phone' => '0922200009', 'email' => 'htetaung@demo.com', 'dob' => '2005-06-14', 'gender' => 1, 'guardian' => 'U Aung'],
            ['name' => 'Ma Wai Phyo', 'phone' => '0922200010', 'email' => 'waiphyo@demo.com', 'dob' => '1998-08-20', 'gender' => 2, 'guardian' => 'Daw Phyo'],
            ['name' => 'U Thant Zin', 'phone' => '0922200011', 'email' => 'thantzin@demo.com', 'dob' => '1955-02-28', 'gender' => 1, 'guardian' => 'Daw Zin'],
            ['name' => 'Ma Ei Mon', 'phone' => '0922200012', 'email' => 'eimon@demo.com', 'dob' => '1992-10-10', 'gender' => 2, 'guardian' => 'U Mon'],
        ];

        $hospitalCode = 'HEKA';
        foreach ($patients as $i => $p) {
            $code = $hospitalCode . str_pad($i + 10, 5, '0', STR_PAD_LEFT);
            $dob = Carbon::parse($p['dob']);
            $patient = \App\Models\Patient::firstOrCreate(
                ['email' => $p['email']],
                [
                    'patient_code' => $code,
                    'patient_folder_name' => $code . '_' . explode(' ', $p['name'])[0],
                    'name' => $p['name'],
                    'phone' => $p['phone'],
                    'password' => Hash::make('password'),
                    'dob' => $p['dob'],
                    'dob_str' => strtotime($p['dob']),
                    'gender' => $p['gender'],
                    'guardian_name' => $p['guardian'],
                    'blood_group' => $bloodGroups[$i % count($bloodGroups)],
                    'status' => 1,
                    'delete_status' => 0,
                    'age_year' => Carbon::now()->diffInYears($dob),
                    'age_month' => Carbon::now()->diffInMonths($dob) % 12,
                ]
            );
            $patientIds[] = $patient->id;
        }

        $this->command->info('  ✓ 12 Patients seeded');

        // ── APPOINTMENTS (15 appointments spanning past week + this week) ─
        $appointmentIds = [];
        $caseCounter = 20000;
        $appointmentDates = [
            Carbon::now()->subDays(5), Carbon::now()->subDays(4), Carbon::now()->subDays(3),
            Carbon::now()->subDays(2), Carbon::now()->subDays(1), Carbon::now(),
            Carbon::now(), Carbon::now(), Carbon::now()->addDays(1),
            Carbon::now()->addDays(1), Carbon::now()->addDays(2), Carbon::now()->addDays(2),
            Carbon::now()->addDays(3), Carbon::now()->addDays(4), Carbon::now()->addDays(5),
        ];

        $statuses = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1]; // all Open

        for ($i = 0; $i < 15; $i++) {
            $caseNum = $caseCounter + $i;
            $date = $appointmentDates[$i];
            $appt = \App\Models\Appointment::firstOrCreate(
                ['case_number' => $caseNum],
                [
                    'patient_id' => $patientIds[$i % count($patientIds)],
                    'patient_name' => '',
                    'hospital_id' => 1,
                    'doctor_staff_id' => $staffIds[$i % count($staffIds)],
                    'appointment_date' => $date->format('Y-m-d'),
                    'appointment_date_str' => $date->timestamp,
                    'casualty_id' => $casualtyIds[$i % count($casualtyIds)],
                    'status' => $statuses[$i],
                    'delete_status' => 0,
                ]
            );
            $appointmentIds[] = $appt->id;
        }

        $this->command->info('  ✓ 15 Appointments seeded');

        // ── PATIENT DIAGNOSIS (MEDICAL RECORDS) (10 records) ────
        $diagnoses = [
            ['diag' => 'Upper Respiratory Tract Infection', 'icd' => 'J06.9', 'symptom' => 'Cough and sore throat for 3 days', 'note' => 'Patient advised rest and hydration'],
            ['diag' => 'Hypertension Stage 1', 'icd' => 'I10', 'symptom' => 'Headache, dizziness', 'note' => 'Started on antihypertensive medication'],
            ['diag' => 'Type 2 Diabetes Mellitus', 'icd' => 'E11.9', 'symptom' => 'Frequent urination, thirst', 'note' => 'Diet and exercise counseling provided'],
            ['diag' => 'Acute Gastritis', 'icd' => 'K29.1', 'symptom' => 'Epigastric pain, nausea', 'note' => 'Avoid spicy food, alcohol. Follow-up in 1 week'],
            ['diag' => 'Lumbar Strain', 'icd' => 'S39.012A', 'symptom' => 'Lower back pain after lifting', 'note' => 'Physical therapy recommended'],
            ['diag' => 'Allergic Rhinitis', 'icd' => 'J30.9', 'symptom' => 'Sneezing, runny nose, itchy eyes', 'note' => 'Intranasal corticosteroid prescribed'],
            ['diag' => 'Migraine without Aura', 'icd' => 'G43.0', 'symptom' => 'Severe unilateral headache with nausea', 'note' => 'Triptan prescribed, avoid triggers'],
            ['diag' => 'Contact Dermatitis', 'icd' => 'L25.9', 'symptom' => 'Red, itchy rash on arms', 'note' => 'Topical steroid cream applied. Avoid irritant'],
            ['diag' => 'Iron Deficiency Anemia', 'icd' => 'D50.9', 'symptom' => 'Fatigue, paleness, shortness of breath', 'note' => 'Iron supplements prescribed. Follow-up blood test in 4 weeks'],
            ['diag' => 'Acute Bronchitis', 'icd' => 'J20.9', 'symptom' => 'Productive cough, mild fever', 'note' => 'Bronchodilator and cough suppressant prescribed'],
        ];

        for ($i = 0; $i < 10; $i++) {
            $d = $diagnoses[$i];
            \App\Models\PatientDiagnosis::firstOrCreate(
                ['appointment_id' => $appointmentIds[$i], 'patient_id' => $patientIds[$i % count($patientIds)]],
                [
                    'staff_id' => $staffIds[$i % count($staffIds)],
                    'diagnosis' => $d['diag'],
                    'icd_diagnosis' => $d['icd'],
                    'treatment_and_intervention_id' => $chargeIds[$i % count($chargeIds)],
                    'height' => rand(155, 185),
                    'weight' => rand(50, 90),
                    'systolic_bp' => rand(110, 150),
                    'diastolic_bp' => rand(65, 95),
                    'pulse' => rand(60, 100),
                    'temperature' => round(rand(365, 390) / 10, 1),
                    'spo2' => rand(94, 99),
                    'respiration' => rand(14, 22),
                    'symptom_type_id' => $symptomIds[$i % count($symptomIds)],
                    'symptom' => $d['symptom'],
                    'description' => $d['diag'] . ' - ' . $d['symptom'],
                    'note' => $d['note'],
                    'checkup_at' => Carbon::now()->subDays(5 - ($i % 5))->format('Y-m-d H:i:s'),
                    'status' => 1,
                    'delete_status' => 0,
                ]
            );
        }

        $this->command->info('  ✓ 10 Patient Diagnosis / Medical Records seeded');

        // ── MEDICAL CERTIFICATES (6 certificates) ──────────────
        $certTypes = ['fitness', 'sick_leave', 'medical', 'fitness', 'sick_leave', 'custom'];
        $certPurposes = [
            'Employment fitness assessment',
            'Sick leave for respiratory infection — 5 days',
            'Annual health checkup certificate',
            'Pre-travel fitness clearance',
            'Post-surgery recovery leave — 14 days',
            'Insurance claim supporting document',
        ];

        for ($i = 0; $i < 6; $i++) {
            \App\Models\MedicalCertificate::firstOrCreate(
                ['certificate_no' => 'CERT-2026-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'patient_id' => $patientIds[$i % count($patientIds)],
                    'appointment_id' => $appointmentIds[$i] ?? null,
                    'doctor_id' => $staffIds[$i % count($staffIds)],
                    'type' => $certTypes[$i],
                    'purpose' => $certPurposes[$i],
                    'issue_date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                    'valid_from' => Carbon::now()->subDays($i)->format('Y-m-d'),
                    'valid_to' => Carbon::now()->addDays(30 - $i)->format('Y-m-d'),
                    'findings' => 'Patient examined. Vitals within normal range. No significant abnormalities detected.',
                    'recommendations' => $i % 2 == 0 ? 'Fit for duty' : 'Rest recommended for recovery period',
                    'restrictions' => $i % 3 == 0 ? 'None' : 'Avoid heavy lifting for 2 weeks',
                    'is_fit' => $i % 2 == 0 ? 1 : 0,
                    'created_by' => 1,
                    'delete_status' => 0,
                ]
            );
        }

        $this->command->info('  ✓ 6 Medical Certificates seeded');

        // ── PHARMACY / MEDICINES (12 medicines) ─────────────────
        $medicines = [
            ['code' => 'P002', 'title' => 'Amoxicillin 500mg', 'company' => 'GSK Pharma', 'unit' => 'Capsule', 'qty' => 500, 'price' => 200],
            ['code' => 'P003', 'title' => 'Metformin 850mg', 'company' => 'Mediline Labs', 'unit' => 'Tablet', 'qty' => 300, 'price' => 150],
            ['code' => 'P004', 'title' => 'Amlodipine 5mg', 'company' => 'Cipla Ltd', 'unit' => 'Tablet', 'qty' => 200, 'price' => 350],
            ['code' => 'P005', 'title' => 'Omeprazole 20mg', 'company' => 'AstraZeneca', 'unit' => 'Capsule', 'qty' => 400, 'price' => 500],
            ['code' => 'P006', 'title' => 'Cetirizine 10mg', 'company' => 'UCB Pharma', 'unit' => 'Tablet', 'qty' => 600, 'price' => 100],
            ['code' => 'P007', 'title' => 'Ibuprofen 400mg', 'company' => 'Sun Pharma', 'unit' => 'Tablet', 'qty' => 800, 'price' => 120],
            ['code' => 'P008', 'title' => 'Salbutamol Inhaler', 'company' => 'GSK Pharma', 'unit' => 'Inhaler', 'qty' => 50, 'price' => 3500],
            ['code' => 'P009', 'title' => 'Vitamin D3 1000IU', 'company' => 'Nature Made', 'unit' => 'Softgel', 'qty' => 1000, 'price' => 250],
            ['code' => 'P010', 'title' => 'Azithromycin 250mg', 'company' => 'Pfizer', 'unit' => 'Tablet', 'qty' => 150, 'price' => 800],
            ['code' => 'P011', 'title' => 'Losartan 50mg', 'company' => 'MSD', 'unit' => 'Tablet', 'qty' => 250, 'price' => 450],
            ['code' => 'P012', 'title' => 'Ferrous Sulfate 325mg', 'company' => 'Mediline Labs', 'unit' => 'Tablet', 'qty' => 300, 'price' => 80],
            ['code' => 'P013', 'title' => 'Dexamethasone Cream 0.1%', 'company' => 'Cipla Ltd', 'unit' => 'Tube', 'qty' => 100, 'price' => 1200],
        ];

        foreach ($medicines as $i => $m) {
            \App\Models\Pharmacy::firstOrCreate(['code' => $m['code']], [
                'pharmacy_category_id' => $pharmCats[$i % count($pharmCats)],
                'title' => $m['title'],
                'company_name' => $m['company'],
                'unit' => $m['unit'],
                'quantity' => $m['qty'],
                'price' => $m['price'],
                'status' => 1,
                'photo' => 'default.png',
                'delete_status' => 0,
            ]);
        }

        $this->command->info('  ✓ 12 Pharmacy items seeded');

        // ── PATHOLOGY TESTS (10 tests) ──────────────────────────
        $pathTests = [
            ['code' => 'CBC', 'test' => 'Complete Blood Count', 'charge' => 3000, 'days' => 1],
            ['code' => 'FBS2', 'test' => 'Fasting Blood Sugar', 'charge' => 2000, 'days' => 1],
            ['code' => 'RBS', 'test' => 'Random Blood Sugar', 'charge' => 1500, 'days' => 1],
            ['code' => 'LFT', 'test' => 'Liver Function Test', 'charge' => 5000, 'days' => 2],
            ['code' => 'RFT', 'test' => 'Renal Function Test', 'charge' => 4500, 'days' => 2],
            ['code' => 'TSH', 'test' => 'Thyroid Stimulating Hormone', 'charge' => 3500, 'days' => 2],
            ['code' => 'UA', 'test' => 'Urinalysis', 'charge' => 1500, 'days' => 1],
            ['code' => 'LP', 'test' => 'Lipid Profile', 'charge' => 4000, 'days' => 2],
            ['code' => 'HBA1C', 'test' => 'HbA1c (Glycated Hemoglobin)', 'charge' => 5500, 'days' => 3],
            ['code' => 'CRP', 'test' => 'C-Reactive Protein', 'charge' => 3000, 'days' => 1],
        ];

        foreach ($pathTests as $i => $t) {
            \App\Models\Pathology::firstOrCreate(['code' => $t['code']], [
                'pathology_category_id' => $pathCats[$i % count($pathCats)],
                'test' => $t['test'],
                'charge' => $t['charge'],
                'report_days' => $t['days'],
                'status' => 1,
                'delete_status' => 0,
            ]);
        }

        $this->command->info('  ✓ 10 Pathology tests seeded');

        // ── RADIOLOGY TESTS (8 tests) ───────────────────────────
        $radTests = [
            ['code' => 'CXR2', 'test' => 'Chest X-Ray PA View', 'charge' => 8000, 'days' => 1],
            ['code' => 'USG-ABD', 'test' => 'Abdominal Ultrasound', 'charge' => 15000, 'days' => 1],
            ['code' => 'CT-HEAD', 'test' => 'CT Scan Head', 'charge' => 45000, 'days' => 1],
            ['code' => 'MRI-SPINE', 'test' => 'MRI Lumbar Spine', 'charge' => 80000, 'days' => 2],
            ['code' => 'ECG', 'test' => 'Electrocardiogram', 'charge' => 5000, 'days' => 1],
            ['code' => 'ECHO', 'test' => 'Echocardiogram', 'charge' => 35000, 'days' => 1],
            ['code' => 'XRAY-KNEE', 'test' => 'X-Ray Knee AP/Lateral', 'charge' => 10000, 'days' => 1],
            ['code' => 'USG-PELVIS', 'test' => 'Pelvic Ultrasound', 'charge' => 18000, 'days' => 1],
        ];

        foreach ($radTests as $i => $t) {
            \App\Models\Radiology::firstOrCreate(['code' => $t['code']], [
                'radiology_category_id' => $radCats[$i % count($radCats)],
                'test' => $t['test'],
                'charge' => $t['charge'],
                'report_days' => $t['days'],
                'status' => 1,
                'delete_status' => 0,
            ]);
        }

        $this->command->info('  ✓ 8 Radiology tests seeded');

        // ── INVENTORY MASTER ITEMS (10 items) ───────────────────
        $masterIds = [];
        $masterItems = [
            ['code' => 'INV001', 'name' => 'Surgical Gloves (Box)', 'unit' => 'Box', 'cat' => 0],
            ['code' => 'INV002', 'name' => 'Face Masks N95', 'unit' => 'Pack', 'cat' => 0],
            ['code' => 'INV003', 'name' => 'Disposable Syringes 5ml', 'unit' => 'Pack (100)', 'cat' => 0],
            ['code' => 'INV004', 'name' => 'Bandage Roll 4inch', 'unit' => 'Roll', 'cat' => 0],
            ['code' => 'INV005', 'name' => 'Digital Thermometer', 'unit' => 'Each', 'cat' => 0],
            ['code' => 'INV006', 'name' => 'Stethoscope', 'unit' => 'Each', 'cat' => 0],
            ['code' => 'INV007', 'name' => 'Cotton Wool 500g', 'unit' => 'Pack', 'cat' => 0],
            ['code' => 'INV008', 'name' => 'Gauze Pad Sterile', 'unit' => 'Pack (50)', 'cat' => 0],
            ['code' => 'INV009', 'name' => 'IV Cannula 20G', 'unit' => 'Box (50)', 'cat' => 0],
            ['code' => 'INV010', 'name' => 'Antiseptic Solution 500ml', 'unit' => 'Bottle', 'cat' => 0],
        ];

        foreach ($masterItems as $i => $item) {
            $master = \App\Models\InventoryItemMaster::firstOrCreate(
                ['master_code' => $item['code']],
                [
                    'item_name' => $item['name'],
                    'inventory_unit' => $item['unit'],
                    'inventory_category_id' => $invCats[$i % count($invCats)],
                    'description' => $item['name'] . ' - Standard hospital supply',
                    'status' => 1,
                    'delete_status' => 0,
                ]
            );
            $masterIds[] = $master->id;
        }

        $this->command->info('  ✓ 10 Inventory master items seeded');

        // ── INVENTORY STOCK (10 stock entries) ──────────────────
        foreach ($masterIds as $i => $masterId) {
            $expiry = Carbon::now()->addMonths(rand(3, 24));
            \App\Models\InventoryStock::firstOrCreate(
                ['item_code' => 'STK' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'batch_number' => 'BN-2026-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'inventory_master_id' => $masterId,
                    'supplier_id' => $supplierIds[$i % count($supplierIds)],
                    'quantity' => rand(20, 200),
                    'balance' => rand(10, 150),
                    'used' => rand(5, 50),
                    'purchase_price' => rand(500, 10000),
                    'selling_price' => rand(800, 15000),
                    'mrp' => rand(1000, 20000),
                    'reorder_level' => rand(10, 30),
                    'date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                    'date_str' => Carbon::now()->subDays(rand(1, 30))->timestamp,
                    'expiry_date' => $expiry->format('Y-m-d'),
                    'description' => 'Standard medical supply item',
                    'status' => 1,
                    'delete_status' => 0,
                ]
            );
        }

        $this->command->info('  ✓ 10 Inventory stock entries seeded');

        // ── REFERRALS (6 referrals) ─────────────────────────────
        $referralData = [
            ['type' => 'outgoing', 'to' => 'Yangon General Hospital', 'spec' => 'Cardiology', 'reason' => 'Advanced cardiac evaluation needed - possible valve replacement'],
            ['type' => 'incoming', 'to' => 'HEKA Clinic', 'spec' => 'Orthopedics', 'reason' => 'Post-fracture follow-up and physiotherapy'],
            ['type' => 'outgoing', 'to' => 'National Eye Centre', 'spec' => 'Ophthalmology', 'reason' => 'Diabetic retinopathy screening'],
            ['type' => 'incoming', 'to' => 'HEKA Clinic', 'spec' => 'General Medicine', 'reason' => 'Chronic disease management'],
            ['type' => 'outgoing', 'to' => 'Children\'s Hospital', 'spec' => 'Pediatrics', 'reason' => 'Pediatric neurology consultation'],
            ['type' => 'incoming', 'to' => 'HEKA Clinic', 'spec' => 'Dermatology', 'reason' => 'Skin biopsy follow-up'],
        ];

        foreach ($referralData as $i => $ref) {
            \App\Models\Referral::firstOrCreate(
                ['patient_id' => $patientIds[$i % count($patientIds)], 'reason' => $ref['reason']],
                [
                    'appointment_id' => $appointmentIds[$i] ?? null,
                    'referral_type' => $ref['type'],
                    'referred_by' => $ref['type'] === 'outgoing' ? 'HEKA Clinic' : 'External Hospital',
                    'referred_to' => $ref['to'],
                    'specialty' => $ref['spec'],
                    'referral_date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                    'status' => $i < 4 ? 'pending' : 'completed',
                    'notes' => 'Referral processed for ' . $ref['spec'] . ' consultation. ' . $ref['reason'],
                    'created_by' => 1,
                    'delete_status' => 0,
                ]
            );
        }

        $this->command->info('  ✓ 6 Referrals seeded');

        $this->command->info('');
        $this->command->info('✅ HEKA Demo Data seeding complete!');
        $this->command->info('   📊 Summary: 10 doctors, 12 patients, 15 appointments,');
        $this->command->info('   10 medical records, 6 certificates, 12 medicines,');
        $this->command->info('   10 lab tests, 8 radiology tests, 10 inventory items, 6 referrals');
    }
}
