<?php

declare(strict_types=1);

namespace App\Mails;

use App\Mails\Base\AbstractSender;

class RegisteredEventSender extends AbstractSender
{
    public function setUserRegisteredMessage(): self
    {
        $this->setSubject('Verify your email address');
//        $this->setTemplate('Verify your email address');

        $data = [
            'titleText' => $this->subject,
            'user'      => $this->user,
            'subject'   => $this->subject,
        ];

        $this->data += $data;
         $this->setTemplateName('mail.user.registered');

        return $this;
    }
}
