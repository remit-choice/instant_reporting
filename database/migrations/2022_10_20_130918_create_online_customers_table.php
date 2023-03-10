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
            $table->bigInteger('customer_id')->index('customer_id')->nullable();
            $table->string('customer_name')->length(2)->nullable();
            $table->string('full_address_with_postcode', 150)->length(2)->nullable();
            $table->string('dob')->length(60)->nullable();
            $table->string('phone')->length(50)->nullable();
            $table->string('email')->length(70)->nullable();
            $table->string('country')->length(50)->nullable();
            $table->string('main_agent')->length(25)->nullable();
            $table->string('registerd_by')->length(30)->nullable();
            $table->string('register_date')->length(50)->nullable();
            $table->double('volume')->nullable();
            $table->double('number_of_transaction')->nullable();
            $table->string('last_transaction_date')->length(50)->nullable();
            $table->string('sales_code')->length(100)->nullable();
            $table->string('state')->length(70)->nullable();
            $table->string('preferred_country')->length(100)->nullable();
            $table->tinyInteger('status')->length(2)->nullable();
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
        Schema::dropIfExists('online_customers');
    }
};
