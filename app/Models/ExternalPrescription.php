<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalPrescription extends Model
{
    protected $table = 'hospital_external_prescriptions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rx_code', 'patient_name', 'patient_phone', 'patient_id',
        'doctor_name', 'doctor_license_no', 'diagnosis', 'instructions',
        'rx_image', 'type', 'rx_date', 'status', 'created_by', 'delete_status'
    ];

    protected $casts = [
        'rx_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function dispensedItems()
    {
        return $this->hasMany(DispensedItem::class, 'external_prescription_id');
    }

    public function sale()
    {
        return $this->hasOne(PharmacySale::class, 'external_prescription_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'amber',
            'dispensed' => 'green',
            'partial' => 'blue',
            'cancelled' => 'red',
            default => 'gray'
        };
    }
}
