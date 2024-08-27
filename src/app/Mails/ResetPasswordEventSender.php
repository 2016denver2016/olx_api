<?php

declare(strict_types=1);

namespace App\Mails;

use App\Mails\Base\AbstractSender;

class ResetPasswordEventSender extends AbstractSender
{
    public function setRecoverPasswordMessage(): self
    {
        $this->setSubject('Reset password');

//        $route = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('reset-password');

        $data = [
            'titleText'   => $this->subject,
            'firstName'   => $this->user->first_name,
            'subject'     => $this->subject,
            'link'        => "https://devapi.letsflowk.com/v1/auth/reset-password?recovery-token={$this->user->password_recovery_token}"
        ];

        $this->data += $data;

        $this->setTemplate(view('mail.user.reset-password', $data)->render());
        // $this->setTemplateName('mail.user.reset-password');

        return $this;
    }
}
