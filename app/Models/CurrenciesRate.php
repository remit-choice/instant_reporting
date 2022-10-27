<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrenciesRate extends Model
{
    protected $fillable = ['id', 'c_id', 'iso', 'iso3', 'currency', 'dated', 'rate', 'status'];
    protected $table = 'currencies_rates';
    use HasFactory;
    public function currencies()
    {
        return $this->hasOne(Currency::class, 'c_id', 'id');
    }
}
