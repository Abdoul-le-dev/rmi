<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddcredentialsColumnToPaymentChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_channels', function (Blueprint $table) {
            $table->text('credentials')->nullable()->after('image');
        });
    }
}