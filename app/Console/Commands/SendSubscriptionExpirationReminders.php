<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\SubscriptionExpirationReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Subscribe;
use App\User;

class SendSubscriptionExpirationReminders extends Command
{

    protected $signature = 'send:subscription-expiration-reminders';
    protected $description = 'Send subscription expiration reminder emails';
    public function handle()
    {
        // $users = User::all();


        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                $activeSubscribe = Subscribe::getActiveSubscribe($user->id);
                $dayOfUse = Subscribe::getDayOfUse($user->id);

                if (!$activeSubscribe || !$dayOfUse) {
                    continue;
                }

                $remainingDays = $activeSubscribe->days - $dayOfUse['days'];

                if (in_array($remainingDays, [7, 3, 1])) {
                    // Pass both the user and remaining days to the Mailable
                    try{
                        Mail::to($user->email)->send(new SubscriptionExpirationReminderMail($user, $remainingDays));
                        Log::info("Message successfully sent to email: {$user->email}");
                    }catch(\Exception $e){
                        Log::info("Failed to send email to {$user->email}: " . $e->getMessage());
                    }
                }
            }
        });
    }
}
