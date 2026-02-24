<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RcmBillItem extends Model
{
    protected $table = 'rcm_bill_items';

    protected $fillable = [
        'bill_number', 'appointment_id', 'patient_id', 'doctor_id', 'diagnosis_id',
        'service_category', 'item_description', 'reference_id', 'reference_type',
        'quantity', 'unit_price', 'line_total', 'bill_date', 'status', 'delete_status'
    ];

    /**
     * Service category constants
     */
    const CATEGORY_CONSULTATION = 'consultation';
    const CATEGORY_PHARMACY     = 'pharmacy';
    const CATEGORY_PATHOLOGY    = 'pathology';
    const CATEGORY_RADIOLOGY    = 'radiology';
    const CATEGORY_PROCEDURE    = 'procedure';
    const CATEGORY_CONSUMABLE   = 'consumable';
    const CATEGORY_OTHER        = 'other';

    public static function categoryLabels(): array
    {
        return [
            self::CATEGORY_CONSULTATION => 'Consultation Fee',
            self::CATEGORY_PHARMACY     => 'Pharmacy',
            self::CATEGORY_PATHOLOGY    => 'Laboratory (Pathology)',
            self::CATEGORY_RADIOLOGY    => 'Radiology / Imaging',
            self::CATEGORY_PROCEDURE    => 'Procedure / Surgery',
            self::CATEGORY_CONSUMABLE   => 'Consumables',
            self::CATEGORY_OTHER        => 'Other Services',
        ];
    }

    public function invoice()
    {
        return $this->belongsTo(RcmInvoice::class, 'bill_number', 'bill_number');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Staff::class, 'doctor_id');
    }
}
