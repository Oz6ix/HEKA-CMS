<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
class UserGroup extends Model
{
    protected $table = 'hospital_user_groups';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'admin_users',
        'staff',
        'patients',
        'appointments',
        'bills',
        'inventory',
        'appointment_report',
        'revenue_report',
        'general_settings',
        'user_groups',
        'notifications',
        'hospital_charges',
        'pharmacy',
        'phatology',
        'radiology',
        'suppliers',
        'configuration',

    ];
    public static $rules = ['title' => 'required | unique:hospital_user_groups'];
    public static function validate_add($data)
    {
        return Validator::make($data, static::$rules);
    }
    public static function validate_update($data, $id)
    {
        $update_rule = static::$rules;
        $update_rule['title'] = 'required | unique:hospital_user_groups,title,' . $id . ',id';
        return Validator::make($data, $update_rule);
    }
    /* Relations */
    public function user()
    {
        return $this->hasMany('App\Models\User', 'group_id');
    }
}
