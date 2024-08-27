<?php

namespace App\Exceptions;

class UserNickNameExists extends Exception
{
    /**
     * @var string
     */
    protected $message = 'This nick name already exists';

    /**
     * @var int
     */
    protected $code = 403;
}
