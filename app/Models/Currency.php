<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'name', 'iso', 'iso3', 'dial', 'currency', 'currency_name', 'min_rate', 'max_rate', 'status', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'currencies';

    public function rates()
    {
        return $this->hasMany(CurrenciesRate::class, 'c_id', 'id');
    }
}
