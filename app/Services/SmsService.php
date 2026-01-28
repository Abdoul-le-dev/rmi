<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SmsService
{
    /**
     * L'URL de base de l'API SMS.to
     */
    private string $baseUrl = 'https://api.sms.to';

    /**
     * La clé API SMS.to
     */
    private string $apiKey;

    /**
     * L'ID de l'expéditeur (optionnel)
     */
    private ?string $senderId;

    /**
     * Constructeur du service
     */
    public function __construct()
    {
        $this->apiKey = config('services.smsto.api_key');
        $this->senderId = config('services.smsto.sender_id', null);

        if (empty($this->apiKey)) {
            throw new Exception('SMS.to API key is not configured');
        }
    }

    /**
     * Envoyer un SMS à un seul destinataire
     *
     * @param string $to Numéro de téléphone au format international (ex: +22997123456)
     * @param string $message Le message à envoyer
     * @param string|null $senderId L'ID de l'expéditeur (optionnel)
     * @return array
     * @throws Exception
     */
    public function send(string $to, string $message, ?string $senderId = null): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/sms/send', [
                'to' => $this->formatPhoneNumber($to),
                'message' => $message,
                'sender_id' => $senderId ?? $this->senderId,
            ]);

            if ($response->failed()) {
                Log::error('SMS.to API Error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                throw new Exception('Failed to send SMS: ' . $response->body());
            }

            $data = $response->json();

            Log::info('SMS sent successfully', [
                'to' => $to,
                'message_id' => $data['message_id'] ?? null,
            ]);

            return [
                'success' => true,
                'message_id' => $data['message_id'] ?? null,
                'data' => $data,
            ];
        } catch (Exception $e) {
            Log::error('SMS sending failed', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Envoyer un SMS à plusieurs destinataires
     *
     * @param array $recipients Tableau de numéros de téléphone
     * @param string $message Le message à envoyer
     * @param string|null $senderId L'ID de l'expéditeur (optionnel)
     * @return array
     */
    public function sendBulk(array $recipients, string $message, ?string $senderId = null): array
    {
        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($recipients as $recipient) {
            try {
                $result = $this->send($recipient, $message, $senderId);
                $results[] = [
                    'recipient' => $recipient,
                    'success' => true,
                    'data' => $result,
                ];
                $successCount++;
            } catch (Exception $e) {
                $results[] = [
                    'recipient' => $recipient,
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
                $failureCount++;
            }
        }

        return [
            'total' => count($recipients),
            'success' => $successCount,
            'failed' => $failureCount,
            'results' => $results,
        ];
    }

    /**
     * Envoyer un SMS de vérification (OTP)
     *
     * @param string $to Numéro de téléphone
     * @param string $code Le code de vérification
     * @return array
     */
    public function sendVerificationCode(string $to, string $code): array
    {
        $message = "Votre code de vérification est: {$code}. Ne le partagez avec personne.";
        return $this->send($to, $message);
    }

    /**
     * Envoyer une notification générique
     *
     * @param string $to Numéro de téléphone
     * @param string $title Titre de la notification
     * @param string $body Corps de la notification
     * @return array
     */
    public function sendNotification(string $to, string $title, string $body): array
    {
        $message = "{$title}\n\n{$body}";
        return $this->send($to, $message);
    }

    /**
     * Vérifier le solde du compte SMS.to
     *
     * @return array
     * @throws Exception
     */
    public function getBalance(): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/balance');

            if ($response->failed()) {
                throw new Exception('Failed to get balance: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Failed to get SMS balance', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Vérifier le statut d'un message
     *
     * @param string $messageId L'ID du message
     * @return array
     * @throws Exception
     */
    public function getMessageStatus(string $messageId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . "/sms/{$messageId}");

            if ($response->failed()) {
                throw new Exception('Failed to get message status: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Failed to get message status', [
                'message_id' => $messageId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Formater le numéro de téléphone au format international
     *
     * @param string $phoneNumber
     * @return string
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Retirer tous les espaces et caractères spéciaux
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);

        // Ajouter le + si absent
        if (!str_starts_with($phoneNumber, '+')) {
            $phoneNumber = '+' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Valider un numéro de téléphone
     *
     * @param string $phoneNumber
     * @return bool
     */
    public function isValidPhoneNumber(string $phoneNumber): bool
    {
        $formatted = $this->formatPhoneNumber($phoneNumber);
        // Vérifier que le numéro commence par + et contient entre 10 et 15 chiffres
        return preg_match('/^\+[1-9]\d{9,14}$/', $formatted) === 1;
    }
}