<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Fortify\TwoFactorAuthenticatable;
// use Laravel\Jetstream\HasProfilePhoto;
// use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    // use HasApiTokens;
    use HasFactory;
    // use HasProfilePhoto;
    use Notifiable;
    // use TwoFactorAuthenticatable; 
    // protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [
        'name',
        'email',
        'password',
    ];*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];





    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'group_id',
        'name',
        'email',
        'password',
        'phone',
        'phone_alternative',
        'profile_photo_path',
        'staff_id',
        'permission_status',
        'reset_pwd_status',
        'status',
        'delete_status'
    ];

    public static $rules = [
        'name' => 'required',
        'email' => 'required|email',
       // 'phone' => 'required|numeric'
    ];


    public static $profile_rules = [
        'email' => 'required|email',
        'name' => 'required'
    ];



    public static function validate_add($data)
    {
        return Validator::make($data, static::$rules);
    }



    public static function validate_update($data, $id)
    {
        $update_rule = static::$rules;
        return Validator::make($data, $update_rule);
    }


    public static function profile_update($data, $id)
    {  

        $update_rule = static::$profile_rules;
        return Validator::make($data, $update_rule);
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path 
                    ? asset('storage/'.$this->profile_photo_path) 
                    : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    /* Relations */
    public function user_group()
    {
            return $this->belongsTo('App\Models\UserGroup', 'group_id');
    }
   
}
