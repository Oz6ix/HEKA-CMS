<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispensedItem extends Model
{
    protected $table = 'hospital_dispensed_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'prescription_id', 'external_prescription_id', 'pharmacy_id',
        'inventory_item_id', 'drug_name', 'quantity_prescribed',
        'quantity_dispensed', 'is_partial', 'unit_price', 'total_price',
        'dispensed_by', 'dispensed_at', 'notes', 'delete_status'
    ];

    protected $casts = [
        'dispensed_at' => 'datetime',
        'is_partial' => 'boolean',
    ];

    public function prescription()
    {
        return $this->belongsTo(PatientPrescription::class, 'prescription_id');
    }

    public function externalPrescription()
    {
        return $this->belongsTo(ExternalPrescription::class, 'external_prescription_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryStock::class, 'inventory_item_id');
    }

    public function dispensedByUser()
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    /**
     * Get remaining quantity to dispense
     */
    public function getRemainingAttribute()
    {
        return max(0, $this->quantity_prescribed - $this->quantity_dispensed);
    }
}
