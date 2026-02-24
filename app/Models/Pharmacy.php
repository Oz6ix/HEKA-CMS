<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class Pharmacy extends Model
{
    protected $table = 'hospital_settings_pharmacy';
    protected $primaryKey = 'id';
    protected $fillable = [
        'pharmacy_category_id', 'title', 'generic_name', 'brand_name',
        'strength', 'form', 'manufacturer', 'schedule', 'medicine_type',
        'barcode', 'code', 'company_name', 'unit', 'quantity', 'price',
        'mrp', 'generic_group_id', 'is_generic', 'hsn_code',
        'photo', 'note', 'status', 'delete_status'
    ];
    public static $rules = [
        'pharmacy_category_id' => 'required',  
        'title' => 'required',              
        'company_name' => 'required',              
        'unit' => 'required',              
        'quantity' => 'required',              
        'price' => 'required',              
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


    public function pharmacy_category(){

        return $this->belongsTo('App\Models\PharmacyCategory', 'pharmacy_category_id');

    }

    /**
     * Get the generic equivalent drugs (branded drugs pointing to this generic)
     */
    public function branded_equivalents()
    {
        return $this->hasMany(Pharmacy::class, 'generic_group_id', 'id');
    }

    /**
     * Get the generic drug this branded drug is equivalent to
     */
    public function generic_drug()
    {
        return $this->belongsTo(Pharmacy::class, 'generic_group_id', 'id');
    }
}

