<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentReminder extends Model
{
    protected $table = 'hospital_appointment_reminders';
    protected $fillable = [
        'appointment_id', 'type', 'recipient_email',
        'scheduled_at', 'sent_at', 'status', 'message', 'delete_status'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'amber',
            'sent' => 'green',
            'failed' => 'red',
            default => 'gray'
        };
    }
}
