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
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('r_id')->index('r_id');
            $table->foreign('r_id')->references('id')->on('roles')->onUpdate('cascade');
            $table->unsignedBigInteger('m_id')->index('m_id');
            $table->foreign('m_id')->references('id')->on('modules')->onUpdate('cascade');
            $table->tinyInteger('view')->length(2)->nullable();
            $table->tinyInteger('add')->length(2)->nullable();
            $table->tinyInteger('edit')->length(2)->nullable();
            $table->tinyInteger('delete')->length(2)->nullable();
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
        Schema::dropIfExists('permissions');
    }
};
