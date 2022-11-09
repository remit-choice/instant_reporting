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
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('m_g_id')->index('m_g_id');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('status');
            $table->bigInteger('sort')->nullable();
            $table->string('type');
            $table->timestamps();
            $table->foreign('m_g_id')->references('id')->on('modules_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
};
