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
            $table->foreign('m_g_id')->references('id')->on('modules_groups')->onUpdate('cascade');
            $table->string('name')->length(60);
            $table->string('icon')->length(50)->nullable();
            $table->integer('sort')->length(11)->nullable();
            $table->integer('type')->length(3);
            $table->tinyInteger('status')->length(2);
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
        Schema::dropIfExists('modules');
    }
};
