<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class Pathology extends Model
{
    protected $table = 'hospital_settings_pathology';
    protected $primaryKey = 'id';
    protected $fillable = ['pathology_category_id', 'test', 'code', 'report_days', 'charge', 'note', 'status', 'delete_status'];
    public static $rules = [
        'pathology_category_id' => 'required',  
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


    public function pathology_category(){

        return $this->belongsTo('App\Models\PathologyCategory', 'pathology_category_id');

    }


}
