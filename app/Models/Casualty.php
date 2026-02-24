<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class Casualty extends Model
{
    protected $table = 'hospital_casualty';
    protected $primaryKey = 'id';
    protected $fillable = ['casualty', 'delete_status', 'status'];
    public static $rules = [
        'casualty' => 'required',               
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
