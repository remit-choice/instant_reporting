<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['id', 'name', 'iso', 'iso3', 'dial', 'currency', 'currency_name', 'rate', 'status', 'created_at', 'updated_at'];
    protected $table = 'currencies';
    use HasFactory;

    public function rates()
    {
        return $this->hasMany(CurrenciesRate::class, 'c_id', 'id');
    }
}
