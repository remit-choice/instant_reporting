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
            $table->foreign('c_id')->references('id')->on('currencies')->onUpdate('cascade');
            $table->string('iso')->length(3);
            $table->string('iso3')->length(3);
            $table->string('currency')->length(50);
            $table->string('dated')->length(60)->nullable();
            $table->float('rate')->length(20)->nullable();
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
        Schema::dropIfExists('currencies_rates');
    }
}
