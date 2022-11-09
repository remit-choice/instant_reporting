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
        Schema::create('modules_urls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('m_id')->index('m_id');
            $table->string('name');
            $table->string('url');
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
        Schema::table('modules_urls', function (Blueprint $table) {
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
        Schema::dropIfExists('modules_urls');
    }
};
