<?php
namespace App\Models;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\SymptomType;
use Illuminate\Database\Eloquent\Model;

class PatientDiagnosis extends Model
{

    protected $table = 'hospital_patient_diagnosis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'appointment_id', 
        'appointment_basic_id', 
        'patient_id', 
        'staff_id',
        'diagnosis', 
        'icd_diagnosis', 
        'treatment_and_intervention_id',
        'height',
        'weight',
        'rbs',
        'height_unit',
        'weight_unit',
        'systolic_bp',
        'diastolic_bp',
        'temperature_unit',
        'pulse', 
        'temperature',
        'spo2',
        'respiration', 
        'symptom_type_id', 
        'symptom', 
        'description', 
        'note',
        'checkup_at',
        'submitted_staff_id',
        'status',
        'delete_status' 
     ];

    public static $rules = [
        'diagnosis'=> 'required', 
/*         'icd_diagnosis'=> 'required',*/
        'treatment_and_intervention_id'=> 'required', 
        'spo2' => 'required|numeric|between:1,100',
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
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_staff_id');
    }
    public function symptom_type()
    {
        return $this->belongsTo(SymptomType::class, 'symptom_type_id');
    }
    public function patient_basic()
    {
        return $this->belongsTo('App\Models\AppointmentBasicsDetail', 'appointment_id');
    }
    public function treatment()
    {
        return $this->belongsTo('App\Models\HospitalCharge', 'treatment_and_intervention_id');
    }
    public function appointment()
    {
        return $this->belongsTo('App\Models\Appointment', 'appointment_id');
    }

   
}
