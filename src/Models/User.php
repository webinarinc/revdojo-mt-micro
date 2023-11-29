<?php

namespace Revdojo\MT\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Revdojo\MT\Traits\Fillable;

class User extends Authenticatable
{
    use HasApiTokens, 
        HasFactory, 
        Notifiable,
        Fillable;

    protected $connection = 'mysql_user_service';
    protected $table = 'users';
    protected $hidden = [
        'password',
    ];

    protected static function boot()
    {
        parent::boot();
        static::bootFillable();
    }

}