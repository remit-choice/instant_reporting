<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulesUrl extends Model
{
    protected $fillable = ['id', 'm_id', 'url', 'name', 'status', 'type'];
    protected $table = 'modules_urls';

    public function modules()
    {
        return $this->belongsTo(Module::class, 'm_id', 'id');
    }
}
