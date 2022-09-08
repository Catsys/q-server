<?php

namespace QServer\Exceptions;

class StorageNotFoundExceptions extends QServerErrorInterface
{
    protected $code = 111;
    protected $message = 'Storage driver is not found';
}