<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class SettingSiteLogo extends Model
{
    protected $table = 'hospital_settings_site_logos';
    protected $primaryKey = 'id';
    protected $fillable = ['favicon', 'logo_mobile_2x', 'logo_desktop','footer_copy_right','homepage_keywords','homepage_title','google_analytics'];
    public static $attribute_names = [
        'logo_mobile' => 'Mobile Logo',
        'logo_mobile_2x' => '2x Mobile Logo',
        'logo_desktop' => 'Desktop Logo'
        ];
    public static $custom_messages = [
        'dimensions' => 'The :attribute should maintain the ratio.'
    ];
    public static function validate_image($data)
    {
         // for calculate aspect ratio
        // https://toolstud.io/photo/aspect.php?width=247&height=69&compare=video   
        $rules = [
            'logo_mobile' => 'dimensions:min_width=' . \Config::get('app.logo_mobile_width') . ',min_height=' . \Config::get('app.logo_mobile_height') . ',ratio=3.58',
            'logo_mobile_2x' => 'dimensions:min_width=' . \Config::get('app.logo_mobile_2x_width') . ',min_height=' . \Config::get('app.logo_mobile_2x_height') . ',ratio=3.58',
            'logo_desktop' => 'dimensions:min_width=' . \Config::get('app.logo_desktop_width') . ',min_height=' . \Config::get('app.logo_desktop_height') . ',ratio=3.58'
        ];
        $validator = Validator::make($data, $rules, static::$custom_messages);
        $validator->setAttributeNames(static::$attribute_names);
        return $validator;
    }
}
