<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerPaymentMethod extends Model
{

    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'country', 'b_id', 'c_id', 'p_m_id', 'rate', 'status', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'buyers_payment_methods';

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
        return $this->hasOne(Currency::class, 'id', 'c_id')->with('rates');
    }
}
