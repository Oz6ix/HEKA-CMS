<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Validator;

class Customer extends Authenticatable /*implements JWTSubject*/
{
    use Notifiable;
    protected $guard = 'blogger';
    protected $table = 'hospital_patients';
    protected $primaryKey = 'id';
    protected $fillable = [
        'customer_code',
        'first_name',
        'last_name',
        'middle_name',
        'agree_privacy_statement',
        'name',
        'email',
        'address',
        'password',
        'phone',
        'reset_pwd_status',
        'note',
        'device_id',
        //'onesignal_player_id',
        //'google_id',
        //'facebook_id',
        'is_android',
        'is_ios',
        'app_version',
        'device_model',
        'status',
        'manual_status',
        'reference_no',
        'delete_status',
        'country_id',
        'phone_code',
        'verification_status',
    ];
    public static $rules = [
        //'name' => 'required',       
        'email' => 'required|email',
        'phone' => 'numeric'
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


    public function customerprofile()

    {

        return $this->hasMany('App\CustomerProfile', 'customer_id');

    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }


    /* Relations */
    /* public function enquiry()
     {
         return $this->hasMany('App\Enquiry', 'customer_id');
     }
     public function wishlist()
     {
         return $this->hasMany('App\Wishlist', 'customer_id');
     }
     public function shopping_cart()
     {
         return $this->hasMany('App\ShoppingCart', 'customer_id');
     }
     public function getJWTIdentifier()
     {
         return $this->getKey();
     }
     public function getJWTCustomClaims()
     {
         return [];
     }*/
}
