<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\PostCreated;
use App\Events\PostUpdated;
use App\Events\PostDeleted;
use App\Events\PollVoted;
use App\Events\PostShared;
use App\Listeners\BroadcastPostCreated;
use App\Listeners\BroadcastPostUpdated;
use App\Listeners\BroadcastPostDeleted;
use App\Listeners\BroadcastPollVoted;
use App\Listeners\BroadcastPostShared;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
         PostCreated::class => [
            BroadcastPostCreated::class,
        ],
        PostUpdated::class => [
            BroadcastPostUpdated::class,
        ],
        PostDeleted::class => [
            BroadcastPostDeleted::class,
        ],
        PollVoted::class => [
            BroadcastPollVoted::class,
        ],
        PostShared::class => [
            BroadcastPostShared::class,
        ],
       
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }

     public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
