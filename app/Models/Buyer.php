<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'name', 'type', 'status', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'buyers';

    public function buyer_payment_methods()
    {
        return $this->hasMany(BuyerPaymentMethod::class, 'b_id', 'id')->with('payment_methods')->with('currencies');
    }
}
