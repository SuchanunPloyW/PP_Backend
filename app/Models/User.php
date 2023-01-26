<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

//auth:sanctum class db_member
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'db_member';
    protected $fillable = [
        'first_name',
        'last_name',
        'nickname',
        'branch',
        'department',
        'tel',
        'email',
        'line_displayname',
        'line_usrid',
        'line_usrphoto',
        'notify_token',
        'verify',
        'status',
        'datetime',
        'password',
    ];
    protected $hidden = [
        'line_usrid',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public $timestamps = false;
}