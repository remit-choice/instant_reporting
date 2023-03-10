<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrenciesReceivingCountries extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'name', 'iso', 'iso3', 'dial', 'currency', 'currency_name', 'status', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'currencies_receiving_countries';
}
