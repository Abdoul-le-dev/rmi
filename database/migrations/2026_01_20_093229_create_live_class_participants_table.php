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
          Schema::create('live_class_participants', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('live_class_id')->constrained()->onDelete('cascade');
             $table->unsignedInteger('user_id') ->nullable();
            $table->foreign('user_id')               
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->string('name'); // Nom du participant
            $table->string('email'); // Email du participant
            $table->boolean('is_moderator')->default(false);
            $table->string('jwt_token', 1000)->nullable(); // Token JWT généré
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->timestamps();

            $table->index('live_class_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_class_participants');
    }
};
