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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('r_id')->index('r_id');
            $table->foreign('r_id')->references('id')->on('roles')->onUpdate('cascade');
            $table->string('full_name')->length(50);
            $table->string('user_name')->length(50);
            $table->string('email')->length(100)->unique();
            $table->string('password')->length(70);
            $table->string('designation')->length(50)->nullable();
            $table->text('image')->nullable();
            $table->string('token')->length(70)->nullable();
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
        Schema::dropIfExists('users');
    }
};
