<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrenciesRate extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'c_id', 'iso', 'iso3', 'currency', 'dated', 'rate', 'status', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'currencies_rates';
    public function currencies()
    {
        return $this->hasOne(Currency::class, 'c_id', 'id');
    }
}
