<?php

namespace QServer\Exceptions;

class OtherExceptions extends QServerErrorInterface
{
    protected $code = 199;
    protected $message = 'Some error happened';
}