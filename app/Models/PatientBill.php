<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PatientBill extends Model
{

    protected $table = 'hospital_patient_bills';
    protected $primaryKey = 'id';
    protected $fillable = [
        'bill_number',
        'appointment_id', 
        'bill_date', 
        'patient_id', 
        'doctor_id', 
        'bill_type', 
        'prescription_id', 
        'medicine_price', 
        'status', 
        'delete_status',
        'diagnosis_id',
        'hospital_charge_id',
        'hospital_charge_price',
        'total',
        'notes', 
        'discount', 
        'discount_price', 
        'tax', 
        'tax_price', 
        'net_amount' ,
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
    public function treatment()
    {
        return $this->belongsTo('App\Models\HospitalCharge', 'hospital_charge_id');
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

   
}
