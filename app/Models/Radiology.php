<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class Radiology extends Model
{
    protected $table = 'hospital_settings_radiology';
    protected $primaryKey = 'id';
    protected $fillable = ['radiology_category_id', 'test', 'code', 'report_days', 'charge', 'note', 'status', 'delete_status'];
    public static $rules = [
        'radiology_category_id' => 'required',  
        'test' => 'required',              
        'code' => 'required',              
        'report_days' => 'required',              
        'charge' => 'required',              
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


    public function radiology_category(){

        return $this->belongsTo('App\Models\RadiologyCategory', 'radiology_category_id');

    }


}
