<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class InventoryStock extends Model
{
    protected $table = 'hospital_inventory_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'item_code', 'batch_number', 'inventory_master_id', 'supplier_id',
        'quantity', 'balance', 'used', 'purchase_price', 'selling_price', 'mrp',
        'reorder_level', 'date_str', 'date', 'expiry_date',
        'description', 'document', 'status', 'delete_status'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'date' => 'date',
    ];

    public static $rules = [         
        'inventory_master_id' => 'required',  
        'supplier_id' => 'required', 
        'quantity' => 'required|numeric',
        'purchase_price' => 'required|numeric',
        'selling_price' => 'required|numeric'
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


   public function supplier(){
        return $this->belongsTo('App\Models\SettingsSupplier', 'supplier_id');
    }

    public function inventorymaster(){
        return $this->belongsTo('App\Models\InventoryItemMaster', 'inventory_master_id');
    }

    public function adjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'inventory_item_id');
    }

    /**
     * Check if item is near expiry (within 30 days)
     */
    public function getIsNearExpiryAttribute()
    {
        if (!$this->expiry_date) return false;
        return $this->expiry_date->isBetween(now(), now()->addDays(30));
    }

    /**
     * Check if item is expired
     */
    public function getIsExpiredAttribute()
    {
        if (!$this->expiry_date) return false;
        return $this->expiry_date->isPast();
    }

    /**
     * Check if stock is low (at or below reorder level)
     */
    public function getIsLowStockAttribute()
    {
        return $this->quantity <= ($this->reorder_level ?? 10);
    }

    /**
     * Scope: near-expiry items (within 30 days)
     */
    public function scopeNearExpiry($query)
    {
        return $query->whereNotNull('expiry_date')
                     ->where('expiry_date', '>', now())
                     ->where('expiry_date', '<=', now()->addDays(30));
    }

    /**
     * Scope: expired items
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
                     ->where('expiry_date', '<', now());
    }

    /**
     * Scope: low stock items
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'reorder_level');
    }
}

