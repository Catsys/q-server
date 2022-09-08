<?php

namespace QServer\Exceptions;

class ArgumentsNotValidExceptions extends QServerErrorInterface
{
    protected $code = 101;
    protected $message = 'Passed arguments is not valid';
}