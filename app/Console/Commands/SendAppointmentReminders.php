<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Mail\AppointmentReminder;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des rappels par email 30 minutes avant les rendez-vous';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jitsiService = app()->make(\App\Services\JitsiService::class);
        $appointments = Appointment::needsReminder()->get();

        $count = 0;

        foreach ($appointments as $appointment) {
            try {

                // Générer les tokens Jitsi

                $instructorToken = $jitsiService->generateInstructorToken(
                    $appointment->meeting_room,
                    $appointment->instructor->full_name,
                    $appointment->instructor->email,
                    $appointment->duration_minutes + 35,
                    false
                );

                $studentToken = $jitsiService->generateStudentToken(
                    $appointment->meeting_room,
                    $appointment->full_name,
                    $appointment->email,
                    $appointment->duration_minutes + 35,
                    false
                );

                // Mettre à jour le rendez-vous
                $appointment->update([
                    'participant_meeting_url' => $studentToken['url'],
                    'participant_token' => $studentToken['token'],
                    'moderator_meeting_url' => $instructorToken['url'],
                    'moderator_token' => $instructorToken['token'],
                    'reminder_sent' => true,
                ]);
                Mail::to($appointment->email)
                    ->send(new AppointmentReminder($appointment));

                $count++;

                $this->info("Rappel envoyé pour le rendez-vous #{$appointment->id}");
            } catch (\Exception $e) {
                $this->error("Erreur pour le rendez-vous #{$appointment->id}: {$e->getMessage()}");
            }
        }

        $this->info("Total de {$count} rappel(s) envoyé(s).");

        return Command::SUCCESS;
    }
}
