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
        Schema::create('poll_options', function (Blueprint $table) {
           $table->increments('id');
           $table->unsignedInteger('poll_id');
           $table->string('option');
           $table->unsignedBigInteger('votes')->default(0);
           $table->timestamps();

           $table->foreign('poll_id')->references('id')->on('polls')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poll_options');
    }
};
