<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('transaction_date')->nullable();
            $table->text('transaction_time')->nullable();
            $table->text('agent_id_collect')->nullable();
            $table->text('agent_name_collect')->nullable();
            $table->text('type')->nullable();
            $table->text('agent_id_main')->nullable();
            $table->text('agent_name_main')->nullable();
            $table->text('office_id')->nullable();
            $table->text('tr_no')->nullable();
            $table->text('pin_no')->nullable();
            $table->text('customer_id')->nullable();
            $table->text('customer_full_name')->nullable();
            $table->text('customer_first_name')->nullable();
            $table->text('customer_last_name')->nullable();
            $table->text('house_no')->nullable();
            $table->text('street')->nullable();
            $table->text('city')->nullable();
            $table->text('post_code')->nullable();
            $table->text('customer_state')->nullable();
            $table->text('customer_country')->nullable();
            $table->text('customer_tel')->nullable();
            $table->text('customer_cell')->nullable();
            $table->text('customer_email')->nullable();
            $table->text('customer_mothername')->nullable();
            $table->text('id_type')->nullable();
            $table->text('id_number')->nullable();
            $table->text('customer_id_issue_place')->nullable();
            $table->text('customer_gender')->nullable();
            $table->text('dob')->nullable();
            $table->text('birth_place')->nullable();
            $table->text('profession')->nullable();
            $table->text('agent_id_pay')->nullable();
            $table->text('agent_name_pay')->nullable();
            $table->text('payment_method')->nullable();
            $table->text('beneficiary_country')->nullable();
            $table->text('customer_rate')->nullable();
            $table->text('agent_rate_')->nullable();
            $table->text('payout_ccy')->nullable();
            $table->text('amount')->nullable();
            $table->text('payin_ccy')->nullable();
            $table->text('payin_amt')->nullable();
            $table->text('admin_charges')->nullable();
            $table->text('agent_charges')->nullable();
            $table->text('beneficiary_full_name')->nullable();
            $table->text('beneficiary_first_name')->nullable();
            $table->text('beneficiary_last_name')->nullable();
            $table->text('receiver_address')->nullable();
            $table->text('receiver_city')->nullable();
            $table->text('receiver_phone')->nullable();
            $table->text('receiver_email')->nullable();
            $table->text('receiver_date_of_birth')->nullable();
            $table->text('receiver_place_of_birth')->nullable();
            $table->text('bank_ac_no')->nullable();
            $table->text('bank_name')->nullable();
            $table->text('branch_name')->nullable();
            $table->text('branch_code')->nullable();
            $table->text('purpose_category')->nullable();
            $table->text('purpose_comments')->nullable();
            $table->text('status')->nullable();
            $table->text('exported')->nullable();
            $table->text('main_hold')->nullable();
            $table->text('subadmin_hold')->nullable();
            $table->text('paid_date')->nullable();
            $table->text('paid_time')->nullable();
            $table->text('buyer_rate')->nullable();
            $table->text('subagent_rate')->nullable();
            $table->text('codice_fiscale')->nullable();
            $table->text('beneficiary_cnic')->nullable();
            $table->text('bene_branch_name')->nullable();
            $table->text('bene_branch_code')->nullable();
            $table->text('bene_bank_name')->nullable();
            $table->text('total_transaction')->nullable();
            $table->text('total_amount')->nullable();
            $table->text('relationship')->nullable();
            $table->text('payment_smethod')->nullable();
            $table->text('payment_type')->nullable();
            $table->text('tmt_no')->nullable();
            $table->text('buyer_dc_rate')->nullable();
            $table->text('customer_register_date')->nullable();
            $table->text('customer_id_1')->nullable();
            $table->text('customer_id_2')->nullable();
            $table->text('log_export_date')->nullable();
            $table->text('last_transaction_date')->nullable();
            $table->integer('file_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions_data');
    }
};
