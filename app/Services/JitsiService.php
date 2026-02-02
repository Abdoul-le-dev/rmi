<?php

namespace App\Services;

use Carbon\Carbon;

class JitsiService
{
    private string $appId;
    private string $appSecret;
    private string $domain;

    public function __construct()
    {
        $this->appId = config('jitsi.app_id');
        $this->appSecret = config('jitsi.app_secret');
        $this->domain = config('jitsi.domain');
    }

    private function base64UrlEncode($data): string
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Génère un token JWT avec support de l'enregistrement
     */
    public function generateToken(
        string $roomName,
        string $userName,
        string $userEmail,
        bool $isModerator = false,
        int $expiresInHours = 24,
        array $additionalContext = []
    ): array {
        $now = Carbon::now();
        $expiresAt = $now->copy()->addHours($expiresInHours);

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $payload = [
            'iss' => $this->appId,
            'aud' => $this->appId,
            'sub' => $this->domain,
            'room' => $roomName,
            'exp' => $expiresAt->timestamp,
            'nbf' => $now->timestamp,
            'iat' => $now->timestamp,
            'moderator' => $isModerator,
            'context' => array_merge([
                'user' => [
                    'name' => $userName,
                    'email' => $userEmail,
                    'moderator' => $isModerator ? 'true' : 'false',
                    'affiliation' => $isModerator ? 'owner' : 'member'
                ]
            ], $additionalContext)
        ];

        $headerEncoded = $this->base64UrlEncode($header);
        $payloadEncoded = $this->base64UrlEncode($payload);

        $signature = hash_hmac(
            'sha256',
            "$headerEncoded.$payloadEncoded",
            $this->appSecret,
            true
        );
        $signatureEncoded = $this->base64UrlEncode($signature);

        $token = "$headerEncoded.$payloadEncoded.$signatureEncoded";
        $url = "https://{$this->domain}/{$roomName}?jwt={$token}";

        return [
            'token' => $token,
            'url' => $url,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * Génère un token pour un instructeur avec enregistrement activé
     */
    public function generateInstructorToken(
        string $roomName,
        string $instructorName,
        string $instructorEmail,
        int $durationMinutes,
        bool $enableRecording = false
    ): array {
        $hours = ceil($durationMinutes / 60) + 1;

        $features = [
            'livestreaming' => config('jitsi.features.livestreaming', true),
            'screen-sharing' => config('jitsi.features.screen_sharing', true),
        ];

        // Activer l'enregistrement si demandé
        if ($enableRecording) {
            $features['recording'] = true;
        }

        return $this->generateToken(
            $roomName,
            $instructorName,
            $instructorEmail,
            true,
            $hours,
            [
                'features' => $features
            ]
        );
    }

    /**
     * Génère un token pour un apprenant
     */
    public function generateStudentToken(
        string $roomName,
        string $studentName,
        string $studentEmail,
        int $durationMinutes
    ): array {
        $hours = ceil($durationMinutes / 60) + 1;

        return $this->generateToken(
            $roomName,
            $studentName,
            $studentEmail,
            false,
            $hours
        );
    }

    /**
     * Génère un token pour un invité externe
     */
    public function generateGuestToken(
        string $roomName,
        string $guestName,
        string $guestEmail,
        int $durationMinutes
    ): array {
        $hours = ceil($durationMinutes / 60) + 1;

        return $this->generateToken(
            $roomName,
            $guestName,
            $guestEmail,
            false,
            $hours
        );
    }

    /**
     * Génère l'URL de base pour rejoindre
     */
    public function getGuestUrl(string $roomName): string
    {
        return "https://{$this->domain}/{$roomName}";
    }

    /**
     * Vérifie si une salle peut être créée
     */
    public function canCreateRoom(string $roomName): bool
    {
        return preg_match('/^[a-zA-Z0-9\-_]+$/', $roomName);
    }
}