<?php

namespace App\Listeners;

use App\Events\UserVerifyEvent;
use App\Jobs\SendEmailVerification as JobsSendEmailVerification;

class SendEmailVerification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserVerifyEvent $event): void
    {
        dispatch(new JobsSendEmailVerification($event->user));
    }
}
