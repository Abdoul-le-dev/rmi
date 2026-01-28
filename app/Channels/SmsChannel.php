<?php

namespace App\Channels;

use App\Services\SmsService;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * Le service SMS
     */
    protected SmsService $smsService;

    /**
     * Create a new channel instance.
     */
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $data = $notification->toSms($notifiable);

        $this->smsService->send(
            $data['to'],
            $data['message'],
            $data['sender_id'] ?? null
        );
    }
}