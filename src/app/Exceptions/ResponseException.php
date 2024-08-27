<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ResponseException extends HttpException
{
    protected $message = 'Unknown instance.';

    public function __construct($message = '')
    {
        if ($message) {
            $this->message = $message;
        }

        parent::__construct(400, $this->message);
    }
}
