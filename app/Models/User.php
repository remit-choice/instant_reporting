<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'r_id', 'full_name', 'user_name', 'email', 'password', 'designation', 'image', 'token', 'status', 'created_at', 'updated_at', 'deleted_at'];
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
