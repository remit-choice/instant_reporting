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
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->length(50);
            $table->string('iso')->length(3);
            $table->string('iso3')->length(3);
            $table->string('dial')->length(25);
            $table->string('currency')->length(50);
            $table->string('currency_name')->length(50);
            $table->float('min_rate')->length(20)->nullable();
            $table->float('max_rate')->length(20)->nullable();
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
        Schema::dropIfExists('currencies');
    }
};
