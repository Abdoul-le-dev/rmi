<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool send($to, string $subject, string $message, array $options = [])
 * @method static bool sendView($to, string $subject, string $view, array $data = [], array $options = [])
 * @method static bool sendWelcomeEmail(string $to, string $userName, array $options = [])
 * @method static bool sendPasswordResetEmail(string $to, string $resetUrl, array $options = [])
 * @method static bool sendVerificationEmail(string $to, string $verificationUrl, array $options = [])
 * @method static bool sendNotification(string $to, string $title, string $content, array $options = [])
 * @method static bool sendVerificationCode(string $to, string $code, array $options = [])
 * @method static array sendBulk(array $recipients, string $subject, string $message, array $options = [])
 * @method static bool sendTemplate(string $to, string $subject, string $title, string $content, array $options = [])
 * @method static bool isValidEmail(string $email)
 * @method static string getFromEmail()
 * @method static string getFromName()
 * @method static self setFromEmail(string $email)
 * @method static self setFromName(string $name)
 *
 * @see \App\Services\MailService
 */
class Mailer extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\MailService::class;
    }
}