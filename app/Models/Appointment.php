<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Appointment extends Model
{
    protected $table = 'hospital_appointments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 
        'patient_id',
        'patient_name',
        'hospital_id', 
        'tpa_id', 
        'case_number', 
        'appointment_date', 
        'appointment_date_str', 
        'reference', 
        'doctor_staff_id', 
        'casualty_id', 
        'add_status', 
        'pharmacy_bill_status', 
        'pathology_bill_status', 
        'radiology_bill_status', 
        'other_bill_status', 
        'status',
        'delete_status'    ];
    public static $rules = [
        'patient_id'=> 'required', 
        'doctor_staff_id'=> 'required', 
        'appointment_date'=> 'required', 
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
    public function patient_blood_group()
    {
        return $this->belongsTo('App\Models\BloodGroup', 'blood_group');
    }
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }
    public function staff_doctor()
    {
        return $this->belongsTo('App\Models\Staff', 'doctor_staff_id');
    }
    public function casualty()
    {
        return $this->belongsTo('App\Models\Casualty', 'casualty_id');
    }
    public function tpa()
    {
        return $this->belongsTo('App\Models\Tpa', 'tpa_id');
    }

}
