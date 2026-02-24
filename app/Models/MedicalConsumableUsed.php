<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class MedicalConsumableUsed extends Model
{

    protected $table = 'hospital_patient_medical_consumable_used';
    protected $primaryKey = 'id';
    protected $fillable = [
        'appointment_id', 
        'staff_id', 
        'patient_id', 
        'item', 
        'item_name', 
        'quantity', 
        'unit_id', 
        'diagnosis_id', 
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
    public function unit()
    {
        return $this->belongsTo('App\Models\Units', 'unit_id');
    }
    public function medical_consumable()
    {
        return $this->belongsTo('App\Models\InventoryStock', 'item');
    }
   
}
