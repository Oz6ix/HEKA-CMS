<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class SettingsConfiguration extends Model
{
    protected $table = 'hospital_configurations';
    protected $primaryKey = 'id';
    protected $fillable = ['enable_pharmacy_status','enable_pathology_status','enable_radiology_status','enable_inventory_status', 'status','delete_status'];
    public static $rules = [
        //'enable_pharmact_status' => 'required',               
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
