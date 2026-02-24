<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class PathologyCategory extends Model
{
    protected $table = 'hospital_settings_pathology_category';
    protected $primaryKey = 'id';
    protected $fillable = ['parent_id','name','description', 'delete_status', 'status'];
    public static $rules = [
        'parent_id' => 'required',  
        'name' => 'required',              
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


    public function subcategory(){

        return $this->belongsTo('App\Models\PathologyCategory', 'parent_id');

    }


}
