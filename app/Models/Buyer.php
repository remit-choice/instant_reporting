<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $fillable = ['id', 'name', 'type', 'status', 'created_at', 'updated_at'];
    protected $table = 'buyers';
    use HasFactory;

    public function buyer_payment_methods()
    {
        return $this->hasMany(BuyerPaymentMethod::class, 'b_id', 'id')->with('payment_methods')->with('currencies');
    }
}
