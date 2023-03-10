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
            $table->string('transaction_date')->length(50)->index('transaction_date')->nullable();
            $table->string('transaction_time')->length(50)->nullable();
            $table->string('agent_id_collect')->length(50)->nullable();
            $table->string('agent_name_collect')->length(35)->nullable();
            $table->string('type')->length(25)->nullable();
            $table->string('agent_id_main')->length(20)->nullable();
            $table->string('agent_name_main')->length(100)->nullable();
            $table->string('office_id')->length(30)->nullable();
            $table->double('tr_no')->nullable();
            $table->string('pin_no')->length(50)->nullable();
            $table->double('customer_id')->nullable();
            $table->string('customer_full_name')->length(100)->nullable();
            $table->string('customer_first_name')->length(50)->nullable();
            $table->string('customer_last_name')->length(50)->nullable();
            $table->string('house_no')->length(100)->nullable();
            $table->string('street')->length(100)->nullable();
            $table->string('city')->length(130)->nullable();
            $table->string('post_code')->length(100)->nullable();
            $table->string('customer_state')->length(70)->nullable();
            $table->string('customer_country')->length(100)->nullable();
            $table->string('customer_tel')->length(30)->nullable();
            $table->string('customer_cell')->length(30)->nullable();
            $table->string('customer_email')->length(70)->nullable();
            $table->string('customer_mothername')->length(50)->nullable();
            $table->string('id_type')->length(70)->nullable();
            $table->string('id_number')->length(100)->nullable();
            $table->string('customer_id_issue_place')->length(20)->nullable();
            $table->string('customer_gender')->length(20)->nullable();
            $table->string('dob')->length(20)->nullable();
            $table->string('birth_place')->length(70)->nullable();
            $table->string('profession')->length(100)->nullable();
            $table->double('agent_id_pay')->nullable();
            $table->string('agent_name_pay')->length(100)->nullable();
            $table->string('payment_method')->length(20)->nullable();
            $table->string('beneficiary_country')->length(100)->nullable();
            $table->string('customer_rate')->length(100)->nullable();
            $table->string('agent_rate')->length(100)->nullable();
            $table->string('payout_ccy')->length(100)->nullable();
            $table->string('amount')->length(100)->nullable();
            $table->string('payin_ccy')->length(100)->nullable();
            $table->string('payin_amt')->length(100)->nullable();
            $table->string('admin_charges')->length(100)->nullable();
            $table->float('agent_charges')->length(20)->nullable();
            $table->string('beneficiary_full_name')->length(100)->nullable();
            $table->string('beneficiary_first_name')->length(50)->nullable();
            $table->string('beneficiary_last_name')->length(50)->nullable();
            $table->string('receiver_address')->length(130)->nullable();
            $table->string('receiver_city')->length(50)->nullable();
            $table->string('receiver_phone')->length(50)->nullable();
            $table->string('receiver_email')->length(70)->nullable();
            $table->string('receiver_date_of_birth')->length(50)->nullable();
            $table->string('receiver_place_of_birth')->length(50)->nullable();
            $table->string('bank_ac_no')->length(70)->nullable();
            $table->string('bank_name')->length(70)->nullable();
            $table->string('branch_name')->length(100)->nullable();
            $table->string('branch_code')->length(70)->nullable();
            $table->string('purpose_category')->length(30)->nullable();
            $table->string('purpose_comments')->length(50)->nullable();
            $table->string('status')->length(25)->nullable();
            $table->string('exported')->length(30)->nullable();
            $table->string('main_hold')->length(50)->nullable();
            $table->string('subadmin_hold')->length(50)->nullable();
            $table->string('paid_date')->length(50)->nullable();
            $table->string('paid_time')->length(50)->nullable();
            $table->float('buyer_rate')->length(30)->nullable();
            $table->float('subagent_rate')->length(30)->nullable();
            $table->string('codice_fiscale')->length(100)->nullable();
            $table->string('beneficiary_cnic')->length(50)->nullable();
            $table->string('receiver_branch_name')->length(100)->nullable();
            $table->string('receiver_branch_code')->length(100)->nullable();
            $table->string('buyer_name')->length(100)->nullable();
            $table->double('total_transaction')->nullable();
            $table->double('total_amount')->nullable();
            $table->string('relationship')->length(50)->nullable();
            $table->string('payment_smethod')->length(70)->nullable();
            $table->string('payment_type')->length(50)->nullable();
            $table->string('tmt_no')->length(100)->nullable();
            $table->string('buyer_dc_rate')->length(30)->nullable();
            $table->string('customer_register_date')->length(100)->nullable();
            $table->string('customer_id_1')->length(100)->nullable();
            $table->string('customer_id_2')->length(100)->nullable();
            $table->string('log_export_date')->length(100)->nullable();
            $table->string('last_transaction_date')->length(100)->nullable();
            $table->string('registered_by')->length(100)->nullable();
            $table->string('transaction_by_device')->length(100)->nullable();
            $table->tinyInteger('file_status')->length(2)->nullable();
            $table->timestamps();
            $table->softDeletes();
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
