<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrenciesReceivingCountries extends Model
{
    protected $fillable = ['id', 'name', 'iso', 'iso3', 'dial', 'currency', 'currency_name', 'rate', 'status', 'created_at', 'updated_at'];
    protected $table = 'currencies_receiving_countries';
    use HasFactory;
}
