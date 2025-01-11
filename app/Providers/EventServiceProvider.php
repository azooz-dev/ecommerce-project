<?php

namespace App\Providers;

use App\Events\ProductOutOfStockEvent;
use App\Events\UserVerifyEvent;
use App\Listeners\SendEmailVerification;
use App\Listeners\UpdateProductStatusListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        ProductOutOfStockEvent::class => [
            UpdateProductStatusListener::class
        ],
        UserVerifyEvent::class => [
            SendEmailVerification::class
        ]
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
