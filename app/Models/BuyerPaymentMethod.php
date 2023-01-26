<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerPaymentMethod extends Model
{

    protected $fillable = ['id', 'b_id', 'c_id', 'p_m_id', 'rate', 'status', 'created_at', 'updated_at'];
    protected $table = 'buyers_payment_methods';
    use HasFactory;

    public function buyers()
    {
        return $this->hasOne(Buyer::class, 'id', 'b_id');
    }
    public function payment_methods()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'p_m_id');
    }
    public function currencies()
    {
        return $this->hasOne(Currency::class, 'id', 'c_id');
    }
}
