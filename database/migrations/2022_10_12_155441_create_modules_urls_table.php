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
            $table->foreign('m_id')->references('id')->on('modules')->onUpdate('cascade');
            $table->string('name')->length(100);
            $table->string('url')->length(100);
            $table->tinyInteger('mode')->length(3);
            $table->tinyInteger('type')->length(3);
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
        Schema::dropIfExists('modules_urls');
    }
};
