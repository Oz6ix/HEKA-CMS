<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientDocument extends Model
{
    protected $table = 'patient_documents';

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'diagnosis_id',
        'uploaded_by',
        'file_name',
        'original_name',
        'file_type',
        'category',
        'notes',
        'delete_status',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get file extension helper
     */
    public function getIsImageAttribute(): bool
    {
        return in_array($this->file_type, ['image', 'jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    public static $categories = [
        'clinical_photo'   => 'Clinical Photo',
        'referral'         => 'Referral Letter',
        'lab_report'       => 'Lab Report',
        'imaging'          => 'Imaging',
        'consent'          => 'Consent Form',
        'external_lab'     => 'External Lab Result',
        'external_imaging' => 'External Imaging',
        'other'            => 'Other',
    ];
}
