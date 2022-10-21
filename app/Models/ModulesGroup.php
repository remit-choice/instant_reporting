<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulesGroup extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'icon'];
    protected $table = 'modules_groups';

    public function modules()
    {
        return $this->hasMany(Module::class, 'm_g_id', 'id')->with('permissions')->with('modules_urls');
    }
}
