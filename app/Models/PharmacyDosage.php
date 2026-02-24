<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyDosage extends Model
{
    use HasFactory;
    protected $table = "hospital_pharmacy_dosage";
    protected $fillable = [
        'dosage','del_status'
    ];
}
