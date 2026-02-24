<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class HospitalCharge extends Model
{
    protected $table = 'hospital_settings_hospital_charges';
    protected $primaryKey = 'id';
    protected $fillable = ['hospital_charge_category_id','code','title','standard_charge','description', 'delete_status', 'status'];
    public static $rules = [
        'hospital_charge_category_id' => 'required',  
        'title' => 'required',              
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


    public function hospital_charge_category(){

        return $this->belongsTo('App\Models\HospitalChargeCategory', 'hospital_charge_category_id');

    }


}
