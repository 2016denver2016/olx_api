<?php

declare(strict_types=1);

namespace App\Mails\Base;

use App\Models\Olx;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

abstract class AbstractSender
{
    protected ?User      $user         = null;
    protected ?Olx       $userAdvert   = null;
    protected bool       $client       = false;
    protected ?string    $subject      = null;
    protected ?string    $template     = null;
    protected ?string    $templateName = null;
    protected object     $settings;
    protected ?string    $address;
    protected string     $fromEmail;
    protected string     $basePath;

    protected $data = [];

    public function __construct()
    {
        $this->fromEmail = env('SENDER_EMAIL');
        $this->basePath = env('BASE_PATH') . 'files';

        $this->data = [
            'contactText' => 'USA, Some City, Some Addr',
            'socials'     => [],
            'address'     => 'Some Addr',
            'basePath'    => $this->basePath,
            'headerImage' => '',
        ];
    }

    public function to(User $user, $userAdvert): self
    {
        $this->user = $user;
        $this->userAdvert = $userAdvert;
        return $this;
    }

    public function send(): void
    {
         Mail::send($this->templateName, $this->data, function ($message) {
             $message->to($this->user->email, $this->user->full_name)->subject($this->subject);
             $message->from(env('MAIL_FROM_NAME'), env('MAIL_FROM_NAME'));
         });
    }

    protected function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function getTemplateName(): ?string
    {
        return $this->templateName;
    }

    protected function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    protected function setTemplateName(string $templateName): self
    {
        $this->templateName = $templateName;

        return $this;
    }
}
