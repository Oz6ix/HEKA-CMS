<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCertificate extends Model
{
    protected $table = 'hospital_medical_certificates';
    protected $fillable = [
        'certificate_no', 'patient_id', 'appointment_id', 'doctor_id',
        'type', 'purpose', 'issue_date', 'valid_from', 'valid_to',
        'findings', 'recommendations', 'restrictions', 'is_fit',
        'created_by', 'delete_status'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_fit' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Staff::class, 'doctor_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public static $types = [
        'fitness' => 'Fitness Certificate',
        'sick_leave' => 'Sick Leave Certificate',
        'medical' => 'Medical Certificate',
        'custom' => 'Custom Certificate',
    ];

    public function getTypeLabelAttribute()
    {
        return self::$types[$this->type] ?? ucfirst($this->type);
    }
}
