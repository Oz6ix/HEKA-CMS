<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class InventoryItemMaster extends Model
{
    protected $table = 'hospital_inventory_master';
    protected $primaryKey = 'id';
    protected $fillable = ['master_code','inventory_category_id','pharmacy_generic','pharmacy_dosage','route','item_name','inventory_unit','description', 'delete_status', 'status'];
    public static $rules = [
        'inventory_category_id' => 'required',  
        'item_name' => 'required',     
        'inventory_unit' => 'required',         
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


    public function inventory_category(){

        return $this->belongsTo('App\Models\InventoryCategory', 'inventory_category_id');

    }


    public function unit(){

        return $this->belongsTo('App\Models\Units', 'inventory_unit');

    }


}
