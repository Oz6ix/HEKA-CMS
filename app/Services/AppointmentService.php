<?php

namespace App\Services;

use App\Repositories\Contracts\AppointmentRepositoryInterface;
use App\Models\AppointmentBasicsDetail;
use App\Models\PatientBriefNote;
use App\Models\PatientDiagnosis;
use App\Models\PatientPrescription;
use App\Models\MedicalConsumableUsed;
use App\Models\PatientMedicalTest;
use App\Models\SettingsSiteGeneral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AppointmentService
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepositoryInterface $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getAllAppointments()
    {
        return $this->appointmentRepository->all();
    }

    public function getAppointmentById($id)
    {
        return $this->appointmentRepository->find($id);
    }

    public function createAppointment(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['appointment_date_str'] = strtotime($data['appointment_date']);
            
            $appointment = $this->appointmentRepository->create($data);
            
            $data['appointment_id'] = $appointment->id;
            AppointmentBasicsDetail::create($data);
            PatientBriefNote::create($data);

            return $appointment;
        });
    }

    public function updateAppointment($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            if (isset($data['appointment_date'])) {
                $data['appointment_date_str'] = strtotime($data['appointment_date']);
            }

            $appointment = $this->appointmentRepository->update($id, $data);
            
            $basicDetails = AppointmentBasicsDetail::where('appointment_id', $id)->first();
            if ($basicDetails) {
                 $basicDetails->update($data);
            }

            return $appointment;
        });
    }

    public function deleteAppointment($id)
    {
        return DB::transaction(function () use ($id) {
            $this->appointmentRepository->delete($id);
            AppointmentBasicsDetail::where('appointment_id', $id)->update(['delete_status' => 1]);
            PatientDiagnosis::where('appointment_id', $id)->update(['delete_status' => 1]);
            PatientPrescription::where('appointment_id', $id)->update(['delete_status' => 1]);
            MedicalConsumableUsed::where('appointment_id', $id)->update(['delete_status' => 1]);
            PatientMedicalTest::where('appointment_id', $id)->update(['delete_status' => 1]);
            return true;
        });
    }

    public function deleteMultipleAppointments($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                    $this->deleteAppointment($id);
                }
            }
            return true;
        }
        return false;
    }

    public function changeStatus($id, $status)
    {
        return $this->appointmentRepository->updateStatus($id, $status);
    }
    
    public function searchAppointments(array $filters)
    {
        // If no filters provided, return all defaults (same as index usually, or empty if specific search required)
        // But repository handles empty filters gracefully
        return $this->appointmentRepository->search($filters);
    }

    public function getDependencyData()
    {
        return [
            'doctors' => $this->appointmentRepository->getDoctors(),
            'patients' => $this->appointmentRepository->getPatients(),
            'symptoms' => $this->appointmentRepository->getSymptoms(),
            'casualties' => $this->appointmentRepository->getCasualties(),
            'tpas' => $this->appointmentRepository->getTpas(),
        ];
    }

    public function getNextCaseNumber()
    {
        $lastAppointment = $this->appointmentRepository->getLastAppointment();
        if (empty($lastAppointment)) {
            return '10000';
        } else {
            return $lastAppointment->case_number + 1;
        }
    }
    
    public function getBasicDetails($id)
    {
        if (PatientDiagnosis::where('appointment_id', $id)->first()) {
            return PatientDiagnosis::where('appointment_id', $id)
                ->where('delete_status', 0)
                ->orderBy('id', 'desc')
                ->first();
        } else {
            return AppointmentBasicsDetail::with('symptom_type')
                ->where('delete_status', 0)
                ->where('appointment_id', $id)
                ->first();
        }
    }

    public function getPrintData($id)
    {
        $hospital_info = SettingsSiteGeneral::first();
        $appt_data = $this->appointmentRepository->find($id);
        $patient_data = $appt_data->patient;
        $staff_data = $appt_data->staff_doctor;
        
        $prescription_data = PatientPrescription::with('unit')
            ->where('appointment_id', $appt_data->id)
            ->orderBy('diagnosis_id', 'Desc')
            ->get();
            
        $vital_data = PatientDiagnosis::where('appointment_id', $id)
            ->orderBy('id', 'DESC')
            ->get();
            
        $cheif_com_data = PatientBriefNote::where('appointment_id', $id)
            ->orderBy('id', 'DESC')
            ->get();
            
        $medical_test = PatientMedicalTest::where('appointment_id', $id)
            ->orderBy('id', 'DESC')
            ->get();

        // Calculate Age
        $today = date('Y-m-d');
        $age = "No data";
        if ($patient_data && $patient_data->dob) {
            $age = date_diff(date_create($patient_data->dob), date_create($today))->y;
        }
        
        $gender = 'No data';
        if ($patient_data->gender == '1') $gender = 'male';
        elseif ($patient_data->gender == '2') $gender = 'female';

        return compact(
            'hospital_info', 
            'appt_data', 
            'patient_data', 
            'staff_data', 
            'prescription_data', 
            'vital_data', 
            'cheif_com_data', 
            'medical_test', 
            'age', 
            'gender'
        );
    }
}
