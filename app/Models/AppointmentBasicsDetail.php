<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class AppointmentBasicsDetail extends Model
{

    protected $table = 'hospital_appointment_basics_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'appointment_id', 
        'patient_id', 
        'doctor_staff_id', 
        'height',
        'height_unit', 
        'weight', 
        'weight_unit',
        'bp', 
        'systolic_bp',
        'diastolic_bp',   
        'pulse', 
        'temperature', 
        'temperature_unit',
        'spo2',
        'respiration', 
        'rbs',
        'symptom_type_id', 
        'symptom', 
        'description', 
        'note', 
        'status', 
        'delete_status' 
     ];

    public static $rules = [
       
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

   
}
