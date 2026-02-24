<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PatientDiagnosisReport extends Model
{
    protected $table = 'hospital_patient_diagnosis_report';
    protected $primaryKey = 'id';
    protected $fillable = [
        'appointment_id', 
        'diagnosis_id', 
        'report_name', 
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
}
