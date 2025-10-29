<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RemovesettingsColumnFrompaymentchannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_channels', function (Blueprint $table) {
            DB::statement("ALTER TABLE `payment_channels` DROP COLUMN `settings`");
        });
    }
}
