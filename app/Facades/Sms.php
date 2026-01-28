<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array send(string $to, string $message, ?string $senderId = null)
 * @method static array sendBulk(array $recipients, string $message, ?string $senderId = null)
 * @method static array sendVerificationCode(string $to, string $code)
 * @method static array sendNotification(string $to, string $title, string $body)
 * @method static array getBalance()
 * @method static array getMessageStatus(string $messageId)
 * @method static bool isValidPhoneNumber(string $phoneNumber)
 *
 * @see \App\Services\SmsService
 */
class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\SmsService::class;
    }
}