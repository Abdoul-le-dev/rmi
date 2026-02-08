<?php

namespace App\Jobs;

use App\Facades\Mailer;
use App\Models\EmailRecipient;
use App\Models\SentEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class SendBulkEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Le nombre de tentatives du job
     */
    public $tries = 3;

    /**
     * Le nombre de secondes avant timeout
     */
    public $timeout = 300;

    /**
     * @var SentEmail
     */
    protected $sentEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(SentEmail $sentEmail)
    {
        $this->sentEmail = $sentEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Marquer comme en cours de traitement
            $this->sentEmail->update(['status' => 'processing']);

            $sentCount = 0;
            $failedCount = 0;

            // Récupérer tous les destinataires en attente
            $recipients = $this->sentEmail->emailRecipients()
                ->where('status', 'pending')
                ->get();

            foreach ($recipients as $recipient) {
                try {
                    // Envoyer l'email
                    $sent = Mailer::send(
                        $recipient->email,
                        $this->sentEmail->subject,
                        $this->sentEmail->content,
                        ['is_html' => true]
                    );

                    if ($sent) {
                        $recipient->markAsSent();
                        $sentCount++;
                    } else {
                        $recipient->markAsFailed('Échec de l\'envoi');
                        $failedCount++;
                    }
                } catch (Exception $e) {
                    $recipient->markAsFailed($e->getMessage());
                    $failedCount++;
                    
                    Log::error('Failed to send email to recipient', [
                        'recipient' => $recipient->email,
                        'error' => $e->getMessage(),
                    ]);
                }

                // Petit délai pour éviter de surcharger le serveur SMTP
                usleep(100000); // 0.1 seconde
            }

            // Mettre à jour les statistiques
            $this->sentEmail->update([
                'sent_count' => $this->sentEmail->sent_count + $sentCount,
                'failed_count' => $this->sentEmail->failed_count + $failedCount,
                'status' => 'completed',
                'sent_at' => now(),
            ]);

            Log::info('Bulk email job completed', [
                'sent_email_id' => $this->sentEmail->id,
                'sent' => $sentCount,
                'failed' => $failedCount,
            ]);

        } catch (Exception $e) {
            $this->sentEmail->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error('Bulk email job failed', [
                'sent_email_id' => $this->sentEmail->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception): void
    {
        $this->sentEmail->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);

        Log::error('Bulk email job permanently failed', [
            'sent_email_id' => $this->sentEmail->id,
            'error' => $exception->getMessage(),
        ]);
    }
}