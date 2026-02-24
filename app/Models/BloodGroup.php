<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class BloodGroup extends Model
{
    protected $table = 'hospital_blood_groups';
    protected $primaryKey = 'id';
    protected $fillable = ['blood_group', 'status','delete_status'];
    public static $rules = [
        'blood_group' => 'required',               
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
