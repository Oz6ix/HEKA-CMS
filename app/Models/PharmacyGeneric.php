<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyGeneric extends Model
{
    use HasFactory;
    protected $table = "hospital_pharmacy_generic";

    protected $fillable = [
        'generic','del_status'
    ];
}
