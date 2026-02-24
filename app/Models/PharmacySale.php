<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacySale extends Model
{
    protected $table = 'hospital_pharmacy_sales';
    protected $primaryKey = 'id';
    protected $fillable = [
        'invoice_no', 'patient_id', 'external_prescription_id',
        'customer_name', 'customer_phone',
        'subtotal', 'discount', 'tax', 'total',
        'payment_method', 'status', 'created_by', 'delete_status'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function externalPrescription()
    {
        return $this->belongsTo(ExternalPrescription::class, 'external_prescription_id');
    }

    public function dispensedItems()
    {
        return $this->hasManyThrough(
            DispensedItem::class,
            ExternalPrescription::class,
            'id', // FK on external_prescriptions
            'external_prescription_id', // FK on dispensed_items
            'external_prescription_id', // Local key on pharmacy_sales
            'id' // Local key on external_prescriptions
        );
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPaymentBadgeAttribute()
    {
        return match($this->payment_method) {
            'cash' => ['color' => 'green', 'icon' => 'fa-money-bill'],
            'card' => ['color' => 'blue', 'icon' => 'fa-credit-card'],
            'upi' => ['color' => 'purple', 'icon' => 'fa-mobile-screen'],
            default => ['color' => 'gray', 'icon' => 'fa-circle']
        };
    }
}
