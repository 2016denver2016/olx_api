<?php

namespace App\Exceptions;

class NotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Entity Not Found Exception';

    /**
     * @var int
     */
    protected $code = 404;
}
