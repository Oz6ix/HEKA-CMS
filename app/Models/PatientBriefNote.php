<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PatientBriefNote extends Model
{

    protected $table = 'hospital_patient_brief_notes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'appointment_id', 
        'staff_id', 
        'patient_id', 
        'cheif_complaint', 
        'cheif_complaint_status', 
        'history_of_present_illness', 
        'history_of_present_illness_status', 
        'past_history', 
        'past_history_status', 
        'physical_examiniation',
        'physical_examiniation_status',
        'status',
        'delete_status' ,
        'diagnosis_id'
     ];

    public static $rules = [
        'cheif_complaint'=> 'required', 

    ];


    public static function validate_add($data)
    {
        return Validator::make($data, static::$rules);
    }

    public static function validate_update($data, $id)
    {
        $update_rule = static::$rules;
        return Validator::make($data, $update_rule);
    }
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }
    public function staff_doctor()
    {
        return $this->belongsTo('App\Models\Staff', 'doctor_staff_id');
    }
    public function symptom_type()
    {
        return $this->belongsTo('App\Models\SymptomType', 'symptom_type_id');
    }
    public function patient_basic()
    {
        return $this->belongsTo('App\Models\AppointmentBasicsDetail', 'appointment_id');
    }

   
}
