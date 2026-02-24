<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Log extends Model
{
    protected $table = 'hospital_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ip_address',
        'admin_id',
        'record_id',
        'action',
        'notes'
    ];
}
