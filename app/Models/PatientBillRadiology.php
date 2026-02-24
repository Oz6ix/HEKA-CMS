<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PatientBillRadiology extends Model
{

    protected $table = 'hospital_patient_bills_radiology';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bill_number', 
        'appointment_id', 
        'diagnosis_id', 
        'bill_date', 
        'patient_id', 
        'doctor_id', 
        'bill_type', 
        'test_id', 
        'test_price', 
        'total', 
        'notes', 
        'discount', 
        'discount_price', 
        'radiology_test_id', 
        'tax', 
        'tax_price', 
        'net_amount', 
        'status', 
        'delete_status'
     ];

    public static $rules = [
        'appointment_id'=> 'required', 
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
        return $this->belongsTo('App\Models\Staff', 'doctor_id');
    }
    public function symptom_type()
    {
        return $this->belongsTo('App\Models\SymptomType', 'symptom_type_id');
    }
    public function patient_basic()
    {
        return $this->belongsTo('App\Models\AppointmentBasicsDetail', 'appointment_id');
    }
    public function unit()
    {
        return $this->belongsTo('App\Models\Units', 'unit_id');
    }
    public function frequency()
    {
        return $this->belongsTo('App\Models\Frequency', 'frequency_id');
    }
    public function center()
    {
        return $this->belongsTo('App\Models\Center', 'reffered_center_id');
    }
    public function bill_medicine()
    {
        return $this->belongsTo('App\Models\PatientPrescription', 'prescription_id');
    }
    public function bill_test()
    {
        return $this->belongsTo('App\Models\PatientMedicalTest', 'test_id');
    }
   
}
