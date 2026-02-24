<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class StaffDocument extends Model
{
    protected $table = 'hospital_staff_documents';
    protected $primaryKey = 'id';
    protected $fillable = ['staff_id','staff_image','resume','document', 'status','delete_status','image_file_type','resume_file_type','document_file_type'];
    public static $rules = [
        //'staff_image' => 'required',               
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
