<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens,HasFactory, HasUuids;

    protected $table = 'user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    protected $hidden =[
        'password',
        'remember_token',
    ];

    public $timestamps = false;

    public static function boot(){
        parent::boot();

        static::created(function($model){
            $model->generate_otp();
        });
    }

    public function generate_otp(){
        do {
            $randomNumber = mt_rand(100000,999999);
            $check = OtpCode::where('otp', $randomNumber)->first();
        } while ($check);

        $now = Carbon::now();

        $otp_code = OtpCode::updateOrCreate(
            ['user_id' => $this->id],
            [
                'otp' => $randomNumber,
                'valid_until' => $now->addMinute(5)
            ]
        );
    }

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return[];
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function otpData(){
        return $this->hasOne(OtpCode::class, 'user_id');
    }
}