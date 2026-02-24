<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class PathologyParameter extends Model
{
    protected $table = 'hospital_settings_pathology_parameters';
    protected $primaryKey = 'id';
    protected $fillable = ['pathology_id', 'parameter_name', 'range', 'unit', 'status', 'delete_status'];
    public static $rules = [
        'pathology_id' => 'required',  
        'parameter_name' => 'required',              
        'range' => 'required',              
        'unit' => 'required',              
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


    public function pathology(){

        return $this->belongsTo('App\Models\Pathology', 'pathology_id');

    }


}
