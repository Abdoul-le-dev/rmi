<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedInteger('instructor_id')->nullable();
            $table->foreign('instructor_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->string('email');
            $table->string('full_name');
            $table->string('subject');
            $table->text('message');
            $table->dateTime('appointment_date');
            $table->integer('duration_minutes')->default(30);
            $table->string('meeting_room')->nullable();
            $table->text('moderator_meeting_url', 1000)->nullable();
            $table->text('participant_meeting_url', 1000)->nullable();
            $table->string('moderator_token', 1000)->nullable();
            $table->string('participant_token', 1000)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->boolean('confirmation_sent')->default(false);
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('appointment_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
