<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class Center extends Model
{
    protected $table = 'hospital_centers';
    protected $primaryKey = 'id';
    protected $fillable = ['center', 'delete_status', 'status'];
    public static $rules = [
        'center' => 'required',               
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
