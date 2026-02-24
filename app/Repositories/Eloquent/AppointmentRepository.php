<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\AppointmentRepositoryInterface;
use App\Models\Appointment;
use App\Models\Staff;
use App\Models\Patient;
use App\Models\SymptomType;
use App\Models\Casualty;
use App\Models\Tpa;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function all()
    {
        return Appointment::with('patient')->with('staff_doctor')
            ->where('delete_status', 0)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function find($id)
    {
        return Appointment::with('patient')
            ->with('staff_doctor')
            ->with('casualty')
            ->with('tpa')
            ->where('delete_status', 0)
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        return Appointment::create($data);
    }

    public function update($id, array $data)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);
        return $appointment;
    }

    public function delete($id)
    {
        return Appointment::where('id', $id)->update(['delete_status' => 1]);
    }

    public function deleteMultiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                    $this->delete($id);
                }
            }
            return true;
        }
        return false;
    }

    public function updateStatus($id, $status)
    {
        return Appointment::where('id', $id)->update(['status' => $status]);
    }

    public function search(array $filters)
    {
        return Appointment::query()
            ->when(isset($filters['doctor_id']) && $filters['doctor_id'], function ($query) use ($filters) {
                return $query->where('doctor_staff_id', $filters['doctor_id']);
            })
            ->when(isset($filters['patient_id']) && $filters['patient_id'], function ($query) use ($filters) {
                return $query->where('patient_id', $filters['patient_id']);
            })
            ->when(isset($filters['case_number']) && $filters['case_number'], function ($query) use ($filters) {
                return $query->where('case_number', $filters['case_number']);
            })
            ->when(isset($filters['appointment_date']) && $filters['appointment_date'], function ($query) use ($filters) {
                return $query->where('appointment_date', $filters['appointment_date']);
            })
            ->where('delete_status', 0)
            ->with('patient', 'staff_doctor')
            ->get();
    }

    public function getDoctors()
    {
        return Staff::select('name', 'id', 'designation_id', 'department_id', 'staff_code')
            ->where('status', 1)
            ->where('delete_status', 0)
            ->whereIn('designation_id', [1, 2])
            ->with('staff_designation')
            ->with('staff_department')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getPatients()
    {
        return Patient::where('status', 1)
            ->where('delete_status', 0)
            ->orderBy('patient_code', 'asc')
            ->get();
    }

    public function getSymptoms()
    {
        return SymptomType::where('status', 1)
            ->where('delete_status', 0)
            ->orderBy('symptom', 'asc')
            ->get();
    }

    public function getCasualties()
    {
        return Casualty::where('status', 1)
            ->where('delete_status', 0)
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getTpas()
    {
        return Tpa::where('status', 1)
            ->where('delete_status', 0)
            ->orderBy('id', 'asc')
            ->get();
    }
    
    public function getLastAppointment()
    {
        return Appointment::orderBy('id', 'desc')->first();
    }
}
