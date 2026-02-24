<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class StaffDesignation extends Model
{
    protected $table = 'hospital_staff_designations';
    protected $primaryKey = 'id';
    protected $fillable = ['designation', 'delete_status', 'status'];
    public static $rules = [
        'designation' => 'required',               
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
