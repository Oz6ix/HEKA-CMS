<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = 'hospital_referrals';
    protected $fillable = [
        'patient_id', 'appointment_id', 'referral_type',
        'referred_by', 'referred_to', 'specialty', 'reason',
        'referral_date', 'status', 'notes', 'created_by', 'delete_status'
    ];

    protected $casts = [
        'referral_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'amber',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function getTypeIconAttribute()
    {
        return $this->referral_type === 'incoming'
            ? 'fa-arrow-right-to-bracket'
            : 'fa-arrow-right-from-bracket';
    }

    public function getTypeLabelAttribute()
    {
        return $this->referral_type === 'incoming' ? 'Incoming' : 'Outgoing';
    }
}
