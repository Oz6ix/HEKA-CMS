<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PatientDiagnosisRepositoryInterface;
use App\Models\PatientDiagnosis;
use App\Models\PatientPrescription;
use App\Models\MedicalConsumableUsed;
use App\Models\PatientMedicalTest;
use App\Models\PatientBill;
use App\Models\InventoryStock;
use App\Models\InventoryItemMaster;
use App\Models\Pathology;
use App\Models\Radiology;
use App\Models\Frequency;
use App\Models\Units;
use App\Models\Center;
use App\Models\HospitalCharge;
use Illuminate\Support\Facades\DB;

class PatientDiagnosisRepository implements PatientDiagnosisRepositoryInterface
{
    public function find($id)
    {
        return PatientDiagnosis::with('treatment')->findOrFail($id);
    }

    public function create(array $data)
    {
        return PatientDiagnosis::create($data);
    }

    public function update($id, array $data)
    {
        $diagnosis = PatientDiagnosis::findOrFail($id);
        $diagnosis->update($data);
        return $diagnosis;
    }

    public function delete($id)
    {
        return PatientDiagnosis::where('id', $id)->update(['delete_status' => 1]);
    }

    public function getByAppointmentId($appointmentId)
    {
        return PatientDiagnosis::with('treatment')
            ->where('appointment_id', $appointmentId)
            ->where('delete_status', 0)
            ->orderBy('id', 'desc')
            ->get();
    }
    
    public function getByPatientId($patientId)
    {
         return PatientDiagnosis::with('treatment', 'staff_doctor', 'appointment')
            ->where('patient_id', $patientId)
            ->where('delete_status', 0)
            ->orderBy('id', 'desc')
            ->get();
    }
    
    public function getHistory($patientId)
    {
        return $this->getByPatientId($patientId);
    }

    // Prescription methods
    public function createPrescription(array $data)
    {
        return PatientPrescription::create($data);
    }
    
    public function updatePrescription($id, array $data)
    {
        return PatientPrescription::updateOrCreate(['id' => $id], $data);
    }
    
    public function deletePrescription($id)
    {
        return PatientPrescription::where('id', $id)->update(['delete_status' => 1]);
    }
    
    public function deletePrescriptionsByDiagnosis($diagnosisId)
    {
        return PatientPrescription::where('diagnosis_id', $diagnosisId)->update(['delete_status' => 1]);
    }
    
    public function getPrescriptionsByDiagnosis($diagnosisId)
    {
        return PatientPrescription::with('unit', 'frequency')
            ->where('diagnosis_id', $diagnosisId)
            ->where('delete_status', 0)
            ->get();
    }

    // Medical Consumable methods
    public function createMedicalConsumable(array $data)
    {
        return MedicalConsumableUsed::create($data);
    }
    
    public function updateMedicalConsumable($id, array $data)
    {
        return MedicalConsumableUsed::updateOrCreate(['id' => $id], $data);
    }
    
    public function deleteMedicalConsumable($id)
    {
        return MedicalConsumableUsed::where('id', $id)->update(['delete_status' => 1]);
    }
    
    public function deleteMedicalConsumablesByDiagnosis($diagnosisId)
    {
        return MedicalConsumableUsed::where('diagnosis_id', $diagnosisId)->update(['delete_status' => 1]);
    }
    
    public function getMedicalConsumablesByDiagnosis($diagnosisId)
    {
         return MedicalConsumableUsed::with('unit', 'medical_consumable')
            ->where('diagnosis_id', $diagnosisId)
            ->where('delete_status', 0)
            ->get();
    }

    // Medical Test methods
    public function createMedicalTest(array $data)
    {
        return PatientMedicalTest::create($data);
    }
    
    public function updateMedicalTest($id, array $data)
    {
        return PatientMedicalTest::updateOrCreate(['id' => $id], $data);
    }
    
    public function deleteMedicalTest($id)
    {
         return PatientMedicalTest::where('id', $id)->update(['delete_status' => 1]);
    }
    
    public function deleteMedicalTestsByDiagnosis($diagnosisId)
    {
        return PatientMedicalTest::where('diagnosis_id', $diagnosisId)->update(['delete_status' => 1]);
    }
    
    public function getMedicalTestsByDiagnosis($diagnosisId)
    {
        return PatientMedicalTest::with('center')
            ->where('diagnosis_id', $diagnosisId)
            ->where('delete_status', 0)
            ->get();
    }

    public function getReportsByDiagnosis($diagnosisId)
    {
        return \App\Models\PatientDiagnosisReport::where('diagnosis_id', $diagnosisId)
            ->where('delete_status', 0)
            ->get();
    }

    // Bill methods
    public function createBill(array $data)
    {
        return PatientBill::create($data);
    }
    
    public function updateBill($diagnosisId, $billType, array $data)
    {
        return PatientBill::where('diagnosis_id', $diagnosisId)
            ->where('bill_type', $billType)
            ->update($data);
    }

    // Dependency methods
    public function getMedicines()
    {
        return DB::table('hospital_inventory_items')
            ->join('hospital_inventory_master', 'hospital_inventory_master.id', 'hospital_inventory_items.inventory_master_id')
            ->where('hospital_inventory_master.inventory_category_id', '3')
            ->get();
    }

    public function getConsumables()
    {
        return InventoryStock::where('delete_status', 0)
            ->where('status', 1)
            ->with('inventorymaster')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getPathologyTests()
    {
        return Pathology::where('delete_status', 0)
            ->orderBy('id', 'desc')
            ->where('status', 1)
            ->select('test', 'code', 'id')
            ->get();
    }

    public function getRadiologyTests()
    {
        return Radiology::where('delete_status', 0)
            ->orderBy('id', 'desc')
            ->where('status', 1)
            ->select('test', 'code', 'id')
            ->get();
    }
    
    public function getFrequencies()
    {
        return Frequency::where('status', 1)
            ->where('delete_status', 0)
            ->orderBy('id', 'asc')
            ->get();
    }
    
    public function getUnits()
    {
        return Units::where('status', 1)
            ->where('delete_status', 0)
            ->orderBy('id', 'asc')
            ->get();
    }
    
    public function getCenters()
    {
        return Center::where('status', 1)
            ->where('delete_status', 0)
            ->orderBy('id', 'asc')
            ->get();
    }
    
    public function getTreatmentCharges()
    {
        return HospitalCharge::where('status', 1)
            ->where('delete_status', 0)
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getSymptoms()
    {
        return \App\Models\SymptomType::where('status', 1)
            ->where('delete_status', 0)
            ->orderBy('symptom', 'asc')
            ->get();
    }
}
