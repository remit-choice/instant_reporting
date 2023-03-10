<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModulesGroup extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'name', 'icon', 'sort', 'status', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'modules_groups';

    public function modules()
    {
        return $this->hasMany(Module::class, 'm_g_id', 'id')->with('permissions')->with('modules_urls')->orderBy('sort');
    }
}
