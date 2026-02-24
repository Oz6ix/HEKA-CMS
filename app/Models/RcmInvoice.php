<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RcmInvoice extends Model
{
    protected $table = 'rcm_invoices';

    protected $fillable = [
        'bill_number', 'bill_type', 'appointment_id', 'patient_id', 'doctor_id',
        'subtotal', 'discount_pct', 'discount_amount', 'tax_pct', 'tax_amount', 'net_amount',
        'notes', 'payment_status', 'payment_mode', 'payment_reference',
        'paid_at', 'paid_by', 'is_credit', 'credit_due_date', 'credit_settled_at',
        'bill_date', 'status', 'delete_status', 'created_by'
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

    public function items()
    {
        return $this->hasMany(RcmBillItem::class, 'bill_number', 'bill_number');
    }

    public function paidByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'paid_by');
    }
}
