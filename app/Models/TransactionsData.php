<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionsData extends Model
{
    protected $fillable = ['id', 'transaction_date', 'transaction_time', 'agent_id_collect', 'agent_name_collect', 'type', 'agent_id_main', 'agent_name_main', 'office_id', 'tr_no', 'pin_no', 'customer_id', 'customer_full_name', 'customer_first_name', 'customer_last_name', 'house_no', 'street', 'city', 'post_code', 'customer_state', 'customer_country', 'customer_tel', 'customer_cell', 'customer_email', 'customer_mothername', 'id_type', 'id_number', 'customer_id_issue_place', 'customer_gender', 'dob', 'birth_place', 'profession', 'agent_id_pay', 'agent_name_pay', 'payment_method', 'beneficiary_country', 'customer_rate', 'agent_rate_', 'payout_ccy', 'amount', 'payin_ccy', 'payin_amt', 'admin_charges', 'agent_charges', 'beneficiary_full_name', 'beneficiary_first_name', 'beneficiary_last_name', 'receiver_address', 'receiver_city', 'receiver_phone', 'receiver_email', 'receiver_date_of_birth', 'receiver_place_of_birth', 'bank_ac_no', 'bank_name', 'branch_name', 'branch_code', 'purpose_category', 'purpose_comments', 'status', 'exported', 'main_hold', 'subadmin_hold', 'paid_date', 'paid_time', 'buyer_rate', 'subagent_rate', 'codice_fiscale', 'beneficiary_cnic', 'bene_branch_name', 'bene_branch_code', 'bene_bank_name', 'total_transaction', 'total_amount', 'relationship', 'payment_smethod', 'payment_type', 'tmt_no', 'buyer_dc_rate', 'customer_register_date', 'customer_id_1', 'customer_id_2', 'log_export_date', 'last_transaction_date', 'file_status', 'created_at', 'updated_at'];
    protected $table = 'transactions_data';
    use HasFactory;
}
