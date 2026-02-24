<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class InventoryCategory extends Model
{
    protected $table = 'hospital_inventory_category';
    protected $primaryKey = 'id';
    protected $fillable = ['parent_id','inventory_name','description', 'delete_status', 'markup', 'status'];
    public static $rules = [
        'parent_id' => 'required',  
        'inventory_name' => 'required',
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

        return $this->belongsTo('App\Models\InventoryCategory', 'parent_id');

    }


}
