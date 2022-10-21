<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrenciesRate extends Model
{
    protected $fillable = ['id', 'c_id', 'dated', 'rate', 'status'];
    protected $table = 'currencies_rates';
    use HasFactory;
}
