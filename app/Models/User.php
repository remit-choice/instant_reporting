<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $fillable = ['id', 'r_id', 'full_name', 'user_name', 'email', 'password', 'image', 'token', 'status', 'designation'];
    protected $table = 'users';

    public function roles()
    {
        return $this->belongsTo(Role::class, 'r_id', 'id');
    }
    public function user_alert()
    {
        return $this->belongsToMany(Alert::class, 'u_id', 'a_id');
    }
}
