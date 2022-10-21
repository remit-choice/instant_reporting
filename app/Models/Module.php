<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['id', 'm_g_id', 'name', 'icon', 'status', 'type', 'sort'];
    protected $table = 'modules';

    public function permissions()
    {
        return $this->belongsTo(Permission::class, 'id', 'm_id');
    }
    public function modules_group()
    {
        return $this->belongsTo(ModulesGroup::class,  'm_g_id', 'id');
    }
    public function modules_urls()
    {
        return $this->hasMany(ModulesUrl::class, 'm_id', 'id');
    }
}
