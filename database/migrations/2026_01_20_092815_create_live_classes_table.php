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
        Schema::create('live_classes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedInteger('instructor_id');
            $table->foreign('instructor_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('room_name')->unique();
            $table->string('live_cover')->nullable();
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes'); // Durée en minutes
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->boolean('is_public')->default(false); // false = seulement apprenants, true = public avec lien
            $table->boolean('auto_record')->default(false);
            $table->boolean('is_being_recorded')->default(false);
            $table->string('public_token')->nullable()->unique(); // Token pour le lien public
            $table->enum('status', ['scheduled', 'live', 'ended', 'cancelled','pending'])->default('pending');
            $table->integer('max_participants')->nullable();
            $table->json('settings')->nullable(); // Paramètres additionnels (enregistrement, etc.)
             
            $table->timestamps();
            $table->softDeletes();


            $table->index('scheduled_at');
            $table->index('status');
            $table->index('instructor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_classes');
    }
};
