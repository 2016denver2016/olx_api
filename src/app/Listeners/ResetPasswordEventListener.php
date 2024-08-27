<?php

namespace App\Listeners;

use App\Events\ResetPasswordEvent;
use App\Mails\ResetPasswordEventSender;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordEventListener implements ShouldQueue
{
    protected $sender;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ResetPasswordEventSender $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Handle the event.
     *
     * @param ResetPasswordEvent $event
     *
     * @return void
     */
    public function handle(ResetPasswordEvent $event)
    {
        $this->sender->to($event->getUser())
            ->setRecoverPasswordMessage()
            ->send();
    }
}
