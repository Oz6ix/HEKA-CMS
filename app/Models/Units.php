<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class Units extends Model
{
    protected $table = 'hospital_units';
    protected $primaryKey = 'id';
    protected $fillable = ['unit', 'status','delete_status'];
    public static $rules = [
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
}
