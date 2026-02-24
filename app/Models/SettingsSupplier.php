<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class SettingsSupplier extends Model
{
    protected $table = 'hospital_suppliers';
    protected $primaryKey = 'id';
    protected $fillable = ['supplier_name','supplier_code','phone','phone_alternative','email','address', 'status','delete_status'];
    public static $rules = [
        'supplier_name' => 'required',        
        'phone' => 'required',
        'email' => 'required',
        'address' => 'required',               
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
