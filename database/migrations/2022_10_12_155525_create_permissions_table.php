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
            $table->unsignedBigInteger('m_id')->index('m_id');
            $table->integer('view')->nullable();
            $table->integer('add')->nullable();
            $table->integer('edit')->nullable();
            $table->integer('delete')->nullable();
            $table->timestamps();
            $table->foreign('r_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('m_id')->references('id')->on('modules')->onDelete('cascade');
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
