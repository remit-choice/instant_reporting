<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'name', 'status', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'payment_methods';

    public function buyer_payment_methods()
    {
        return $this->hasMany(BuyerPaymentMethod::class, 'p_m_id', 'id')->with('buyers');
    }
}
