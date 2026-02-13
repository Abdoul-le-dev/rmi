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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->unsignedInteger('user_id');
            
            // Montant du retrait
            $table->decimal('amount', 15, 2);
            
            // Méthode de paiement
            $table->enum('payment_method', ['bank', 'mobile_money']);
            
            // Informations bancaires (pour bank)
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('iban')->nullable();
            $table->string('swift_code')->nullable();
            
            // Informations Mobile Money
            $table->string('mobile_operator')->nullable(); // MTN, Moov, etc.
            $table->string('mobile_number')->nullable();
            $table->string('mobile_account_name')->nullable();
            
            // Statut de la demande
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])
                  ->default('pending');
            
            // Motif de rejet
            $table->text('rejection_reason')->nullable();
            
            // Date de traitement
            $table->timestamp('processed_at')->nullable();
            $table->unsignedInteger('processed_by')->nullable(); // Admin qui a traité
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('processed_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Index pour améliorer les performances
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawals');
    }
};
