<?php

namespace App\Exceptions;

class InvalidPasswordException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Password incorrect Exception';

    /**
     * @var int
     */
    protected $code = 401;

}
