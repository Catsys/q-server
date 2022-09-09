<?php

namespace QServer\Exceptions;

class AlreadyRunningException extends QServerErrorInterface
{
    protected $code = 102;
    protected $message = 'Another script instance is already running';
}