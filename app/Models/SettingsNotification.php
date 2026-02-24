<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class SettingsNotification extends Model
{
    protected $table = 'hospital_settings_notifications';
    protected $primaryKey = 'id';
    protected $fillable = ['patient_registration_email', 'patient_phone','appointment_booking_email','appointment_phone','inventory_stock_email','inventory_stock_phone'];
    public static $rules = [
        //'patient_registration_email' => 'required',
        //'patient_phone' => 'required',
        //'appointment_booking_email' => 'required',
        //'appointment_phone' => 'required',
        //'inventory_stock_email' => 'required',
        //'inventory_stock_phone' => 'required',            
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
