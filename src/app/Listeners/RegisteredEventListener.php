<?php

namespace App\Listeners;

use App\Events\RegisteredEvent;
use App\Mails\RegisteredEventSender;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisteredEventListener implements ShouldQueue
{
    protected $sender;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(RegisteredEventSender $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Handle the event.
     *
     * @param RegisteredEvent $event
     *
     * @return void
     */
    public function handle(RegisteredEvent $event)
    {
        $this->sender->to($event->getUser())
            ->setUserRegisteredMessage()
            ->send();
    }
}
