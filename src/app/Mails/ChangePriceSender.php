<?php

declare(strict_types=1);

namespace App\Mails;

use App\Mails\Base\AbstractSender;

class ChangePriceSender extends AbstractSender
{
    public function setUserAdvertChangePriceMessage(): self
    {
        $this->setSubject('The price in this ad has changed');

        $data = [
            'titleText'  => $this->subject,
            'user'       => $this->user,
            'userAdvert' => $this->userAdvert,
            'subject'    => $this->subject,
        ];

        $this->data += $data;
         $this->setTemplateName('mail.user.price');

        return $this;
    }
}
