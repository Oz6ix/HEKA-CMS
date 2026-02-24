<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PatientPrescription extends Model
{

    protected $table = 'hospital_patient_prescriptions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'appointment_id', 
        'drug_name', 
        'quantity', 
        'unit_id', 
        'frequency_id', 
        'no_of_days', 
        'staff_id', 
        'patient_id',
        'drug_id',
        'status',
        'delete_status',
        'diagnosis_id'
     ];

    public static $rules = [
        'prescription.*.drug_name' => 'required',
        'prescription.*.unit_id' => 'required',
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
    public function unit()
    {
        return $this->belongsTo('App\Models\Units', 'unit_id');
    }
    public function frequency()
    {
        return $this->belongsTo('App\Models\Frequency', 'frequency_id');
    }
    public function pharmacy_data()
    {
        return $this->belongsTo('App\Models\InventoryItemMaster', 'drug_id');
    }
   
}
