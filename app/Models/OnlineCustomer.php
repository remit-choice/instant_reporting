<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineCustomer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'customer_id', 'customer_name', 'full_address_with_postcode', 'dob', 'phone', 'email', 'country', 'main_agent', 'registerd_by', 'register_date', 'volume', 'number_of_transaction', 'last_transaction_date', 'sales_code', 'state', 'preferred_country', 'status', 'created_at', 'updated_at', 'deleted_at'];
    protected $table = 'online_customers';
    public function transactions()
    {
        return $this->hasMany(TransactionsData::class, 'customer_id', 'customer_id');
    }
}
