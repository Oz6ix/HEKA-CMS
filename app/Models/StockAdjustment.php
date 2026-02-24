<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $table = 'hospital_stock_adjustments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'inventory_item_id', 'type', 'quantity', 'reason',
        'adjusted_by', 'adjustment_date', 'notes'
    ];

    protected $casts = [
        'adjustment_date' => 'date',
    ];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryStock::class, 'inventory_item_id');
    }

    public function adjustedByUser()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    /**
     * Get human-readable type label
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'damage' => 'Damaged',
            'expiry' => 'Expired',
            'loss' => 'Lost',
            'return' => 'Returned',
            'correction' => 'Correction',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get badge color for type
     */
    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'damage' => 'red',
            'expiry' => 'amber',
            'loss' => 'orange',
            'return' => 'blue',
            'correction' => 'gray',
            default => 'gray'
        };
    }
}
