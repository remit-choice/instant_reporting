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
        Schema::create('online_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->nullable();
            $table->text('customer_name')->nullable();
            $table->text('full_address_with_postcode')->nullable();
            $table->text('dob')->nullable();
            $table->text('phone')->nullable();
            $table->text('email')->nullable();
            $table->text('country')->nullable();
            $table->text('main_agent')->nullable();
            $table->text('registerd_by')->nullable();
            $table->text('register_date')->nullable();
            $table->text('volume')->nullable();
            $table->text('number_of_transaction')->nullable();
            $table->text('last_transaction_date')->nullable();
            $table->text('sales_code')->nullable();
            $table->text('state')->nullable();
            $table->text('preferred_country')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('online_customers');
    }
};
