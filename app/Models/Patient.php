<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Patient /*extends Model*/ extends Authenticatable
{

    protected $table = 'hospital_patients';
    protected $primaryKey = 'id';
    protected $fillable = [
        'patient_code', 
        'hospital_id', 
        'patient_folder_name', 
        'name', 
        'phone', 
        'phone_alternative', 
        'email', 
        'password', 
        'dob', 
        'dob_str', 
        'gender', 
        'guardian_name', 
        'blood_group', 
        'marital_status', 
        'any_known_allergies', 
        'patient_photo', 
        'age_year',
        'age_month',
        'address', 
        'remark', 
        'status', 
        'delete_status',
        'add_status'    ];

 public static $rules = [
        'patient_code'=> 'required', 
        'name'=> 'required', 
        'phone'=> 'required', 
         
        'dob'=> 'required', 
        'gender'=> 'required', 
        'guardian_name'=> 'required', 
        'blood_group'=> 'required', 
        'any_known_allergies'=> 'required', 
    ];

    public static $update_rules = [
        'name'=> 'required', 
        'phone'=> 'required', 
         
        'dob'=> 'required', 
        'gender'=> 'required', 
        'blood_group'=> 'required', 
    ];

    public static function validate_add($data)
    {
        return Validator::make($data, static::$rules);
    }

   public static function validate_update($data, $id)
    {
        $update_rule = static::$update_rules;
        return Validator::make($data, $update_rule);
    }
    
    public function patient_blood_group()
    {
        return $this->belongsTo('App\Models\BloodGroup', 'blood_group');
    }

   
}
