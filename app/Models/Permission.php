<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'r_id', 'm_id', 'view', 'add', 'edit', 'delete', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'permissions';

    public function modules()
    {
        return $this->belongsTo(Module::class, 'id', 'm_id');
    }
    public function modules_urls()
    {
        return $this->belongsTo(ModulesUrl::class, 'm_id', 'm_id');
    }
}
