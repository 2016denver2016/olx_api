<?php

namespace App\Providers;

use App\Events\ExampleEvent;
use App\Events\NotificationAfterNewVideoEvent;
use App\Events\RegisteredEvent;
use App\Events\ResetPasswordEvent;
use App\Listeners\ExampleListener;
use App\Listeners\NotificationAfterNewVideoEventListener;
use App\Listeners\RegisteredEventListener;
use App\Listeners\ResetPasswordEventListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ExampleEvent::class => [
            ExampleListener::class,
        ],
        NotificationAfterNewVideoEvent::class => [
            NotificationAfterNewVideoEventListener::class,
        ],
        RegisteredEvent::class => [
            RegisteredEventListener::class,
        ],
        ResetPasswordEvent::class => [
            ResetPasswordEventListener::class,
        ],
    ];
}
