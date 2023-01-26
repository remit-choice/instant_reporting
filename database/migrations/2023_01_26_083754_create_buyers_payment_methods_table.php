<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyersPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers_payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('b_id')->index('b_id')->nullable()->onDelete('cascade');
            $table->foreign('b_id')->references('id')->on('buyers');
            $table->unsignedBigInteger('c_id')->index('c_id')->nullable()->onDelete('cascade');
            $table->foreign('c_id')->references('id')->on('currencies');
            $table->unsignedBigInteger('p_m_id')->index('p_m_id')->nullable()->onDelete('cascade');
            $table->foreign('p_m_id')->references('id')->on('payment_methods');
            $table->string('rate')->nullable();
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
        Schema::dropIfExists('buyers_payment_methods');
    }
}
