<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['id', 'name', 'status', 'created_at', 'updated_at'];
    protected $table = 'payment_methods';
    use HasFactory;

    public function buyer_payment_methods()
    {
        return $this->hasMany(BuyerPaymentMethod::class, 'p_m_id', 'id')->with('buyers');
    }
}
