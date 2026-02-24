<?php

namespace App\Services;

use App\Repositories\Contracts\PatientRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PatientService
{
    protected $patientRepository;

    public function __construct(PatientRepositoryInterface $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function getAllPatients()
    {
        return $this->patientRepository->getAll();
    }

    public function getPatientById($id)
    {
        return $this->patientRepository->find($id);
    }

    public function createPatient(array $data)
    {
        // Handle password hashing if provided, otherwise default to 123456
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
             $data['password'] = Hash::make('123456');
        }

        // Generate patient code if not provided (though logic implies it might be generated in controller previously, better here)
        // Previous controller logic: $data['patient_code'] = 'P' . time();
        // $data['patient_code'] = 'P' . time();

        return $this->patientRepository->create($data);
    }

    public function updatePatient($id, array $data)
    {
        return $this->patientRepository->update($id, $data);
    }

    public function deletePatient($id)
    {
        return $this->patientRepository->delete($id);
    }

    public function deleteMultiplePatients(array $ids)
    {
        return $this->patientRepository->deleteMultiple($ids);
    }

    public function updatePatientStatus($id, $status)
    {
        return $this->patientRepository->updateStatus($id, $status);
    }
    
    public function checkExists($data, $id = null)
    {
        return $this->patientRepository->exists($data, $id);
    }

    public function checkDuplicateEmail($email)
    {
        return $this->patientRepository->findByEmail($email) ? true : false;
    }

    public function handleImageUpload($file)
    {
         if ($file && $file->isValid()) {
             $filename = 'patient_' . time() . '.' . $file->getClientOriginalExtension();
             // Using 'uploads' disk as per previous configuration
             $path = $file->storeAs('patient', $filename, 'uploads');
             return 'patient/' . $filename;
         }
         return null;
    }
}
