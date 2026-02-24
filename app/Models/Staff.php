<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class Staff extends Model
{
    protected $table = 'hospital_staffs';
    protected $primaryKey = 'id';
    protected $fillable = ['staff_code','hospital_id','designation_id','department_id','role_id','name','phone','phone_alternative','email','current_address','permanent_address','facebook_url',
    'linkedin_url','twitter_url','instagram_url','specialist_id','gender','maritial_status','blood_group','dob','dob_str','date_join','date_join_str','qualification','work_experience','note','permission_admin_access','status','delete_status','staff_directory'];
    public static $rules = [
        'designation_id' => 'required',   
        'department_id' => 'required', 
        'role_id' => 'required', 
        'specialist_id' => 'required',             
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

    public function staff_blood_group()
    {
        return $this->belongsTo('App\Models\BloodGroup', 'blood_group');

    }


    public function staff_role()
    {
        return $this->belongsTo('App\Models\StaffRole', 'role_id');

    }


    public function staff_designation()
    {
        return $this->belongsTo('App\Models\StaffDesignation', 'designation_id');

    }

    public function staff_department()
    {
        return $this->belongsTo('App\Models\StaffDepartment', 'department_id');

    }

    public function staff_specialist()
    {
        return $this->belongsTo('App\Models\StaffSpecialist', 'specialist_id');

    }

    public function staff_group()
    {
        return $this->belongsTo('App\Models\UserGroup', 'group_id');

    }


    public function staff_user_group()
    {
        return $this->hasMany('App\Models\User', 'staff_id');
    }

     public function staff_document()
    {
        return $this->hasMany('App\Models\StaffDocument', 'staff_id');
    }



}
