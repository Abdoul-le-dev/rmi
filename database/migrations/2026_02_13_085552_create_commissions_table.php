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
         Schema::create('commissions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->unsignedInteger('affiliate_user_id');

            // Filleul (acheteur)
            $table->unsignedInteger('referred_user_id');

            // Commande liée
            $table->unsignedInteger('order_id');

            // Montant commission
            $table->decimal('amount', 15, 2);

            // Statut
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('approved');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('affiliate_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('referred_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');
            // Empêche double commission pour même commande
            $table->unique(['affiliate_user_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commissions');
    }
};
