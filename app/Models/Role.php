<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['id', 'name'];
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
