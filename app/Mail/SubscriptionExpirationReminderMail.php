<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;

class SubscriptionExpirationReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $remainingDays;

    /**
     * Create a new message instance.
     *
     * @param int $remainingDays
     */
    public function __construct($user, $remainingDays)
    {
        $this->user = $user;
        $this->remainingDays = $remainingDays;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Subscription Expiration Reminder')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->view('web.default.emails.subscription_expiration')
                    ->with([
                        'user' => $this->user,
                        'remainingDays' => $this->remainingDays,
                    ]);
    }
}
