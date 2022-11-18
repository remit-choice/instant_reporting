<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCustomer extends Model
{
    protected $fillable = ['id', 'customer_id', 'customer_name', 'full_address_with_postcode', 'dob', 'phone', 'email', 'country', 'main_agent', 'registerd_by', 'register_date',  'volume', 'number_of_transaction', 'last_transaction_date', 'sales_code', 'state', 'preferred_country', 'status'];
    protected $table = 'online_customers';
    use HasFactory;
    public function transactions()
    {
        return $this->hasMany(TransactionsData::class, 'customer_id', 'customer_id');
    }
}
