<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'name', 'status', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'roles';
    public function employees()
    {
        return $this->hasMany(Employee::class, 'r_id', 'id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'r_id', 'id');
    }
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'r_id', 'id');
    }
}
