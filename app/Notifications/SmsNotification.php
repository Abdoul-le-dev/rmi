<?php

namespace App\Notifications;

use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SmsNotification extends Notification
{
    use Queueable;

    /**
     * Le message à envoyer
     */
    private string $message;

    /**
     * L'ID de l'expéditeur (optionnel)
     */
    private ?string $senderId;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message, ?string $senderId = null)
    {
        $this->message = $message;
        $this->senderId = $senderId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms(object $notifiable): array
    {
        return [
            'to' => $notifiable->phone ?? $notifiable->phone_number,
            'message' => $this->message,
            'sender_id' => $this->senderId,
        ];
    }
}