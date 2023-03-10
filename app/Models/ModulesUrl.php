<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModulesUrl extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'm_id', 'name', 'url', 'mode', 'type', 'status', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'modules_urls';

    public function modules()
    {
        return $this->belongsTo(Module::class, 'm_id', 'id');
    }
}
