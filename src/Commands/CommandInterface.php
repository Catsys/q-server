<?php

namespace QServer\Commands;

interface CommandInterface
{
    public function run(array $data = []);
    public function isValid(array $data = []):bool;
}