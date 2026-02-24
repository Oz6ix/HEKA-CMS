<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PatientMedicalTest extends Model
{

    protected $table = 'hospital_patient_medical_tests';
    protected $primaryKey = 'id';
    protected $fillable = [
        'appointment_id', 
        'staff_id', 
        'patient_id', 
        'diagnosis_id', 
        'pathology_test_id', 
        'radiology_test_id', 
        'test_name', 
        'reffered_center_id', 
        'status', 
        'delete_status',
        'result_value',
        'result_unit',
        'reference_range',
        'interpretation',
        'result_notes',
        'result_date',
        'result_entered_by',
        'result_entered_at',
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
    public function center()
    {
        return $this->belongsTo('App\Models\Center', 'reffered_center_id');
    }
    public function pathology_data()
    {
        return $this->belongsTo('App\Models\Pathology', 'pathology_test_id');
    }
    public function radiology_data()
    {
        return $this->belongsTo('App\Models\Radiology', 'radiology_test_id');
    }
   
}
