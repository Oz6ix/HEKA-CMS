<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class SettingsSiteGeneral extends Model
{
    protected $table = 'hospital_settings_general_infos';
    protected $primaryKey = 'id';
    protected $fillable = ['hospital_name', 'hospital_address', 'contact_email', 'contact_phone','alternative_phone','hospital_code','facebook_url','twitter_url','instagram_url','youtube_url','linkedin_url'];
    public static $rules = [
        'hospital_name' => 'required',
        'hospital_address' => 'required',
        'contact_phone' => 'required'
    ];
    public static function validate_update($data, $id)
    {
        $update_rule = static::$rules;
        return Validator::make($data, $update_rule);
    }
}
