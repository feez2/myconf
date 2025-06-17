<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
usE App\Events\PaperSubmittedEvent;
use App\Events\ReviewAssignedEvent;
use App\Listeners\SendPaperSubmittedNotification;
use App\Listeners\SendReviewAssignedNotification;
use App\Listeners\SendDecisionNotification;
use App\Events\DecisionMadeEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PaperSubmittedEvent::class => [
            SendPaperSubmittedNotification::class,
        ],
        ReviewAssignedEvent::class => [
            SendReviewAssignedNotification::class,
        ],
        DecisionMadeEvent::class => [
            SendDecisionNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
