<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class StaffRole extends Model
{
    protected $table = 'hospital_staff_roles';
    protected $primaryKey = 'id';
    protected $fillable = ['role', 'status','delete_status'];
    public static $rules = [
        'role' => 'required',               
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
