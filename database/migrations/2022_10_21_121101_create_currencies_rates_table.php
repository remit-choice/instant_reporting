<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('c_id')->index('c_id');
            $table->string('iso')->nullable();
            $table->string('iso3')->nullable();
            $table->string('currency')->nullable();
            $table->string('dated')->nullable();
            $table->string('rate')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->foreign('c_id')->references('id')->on('currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies_rates');
    }
}
