<?php

namespace App\Repositories\Contracts;

interface PatientDiagnosisRepositoryInterface
{
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    
    // Diagnosis specific methods
    public function getByAppointmentId($appointmentId);
    public function getByPatientId($patientId);
    public function getHistory($patientId);
    
    // Related entities methods
    public function createPrescription(array $data);
    public function updatePrescription($id, array $data);
    public function deletePrescription($id);
    public function deletePrescriptionsByDiagnosis($diagnosisId);
    public function getPrescriptionsByDiagnosis($diagnosisId);
    
    public function createMedicalConsumable(array $data);
    public function updateMedicalConsumable($id, array $data);
    public function deleteMedicalConsumable($id);
    public function deleteMedicalConsumablesByDiagnosis($diagnosisId);
    public function getMedicalConsumablesByDiagnosis($diagnosisId);
    
    public function createMedicalTest(array $data);
    public function updateMedicalTest($id, array $data);
    public function deleteMedicalTest($id);
    public function deleteMedicalTestsByDiagnosis($diagnosisId);
    public function getMedicalTestsByDiagnosis($diagnosisId);
    
    public function getReportsByDiagnosis($diagnosisId);
    
    public function createBill(array $data);
    public function updateBill($diagnosisId, $billType, array $data);
    
    // Dependencies
    public function getMedicines();
    public function getConsumables();
    public function getPathologyTests();
    public function getRadiologyTests();
    public function getFrequencies();
    public function getUnits();
    public function getCenters();
    public function getTreatmentCharges();
    public function getSymptoms();
}
