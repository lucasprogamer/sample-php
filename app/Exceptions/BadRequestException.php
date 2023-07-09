<?php

namespace App\Exceptions;


class BadRequestException extends \Exception
{
    protected $code = 400;
}
