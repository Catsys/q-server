<?php

namespace QServer\Jobs;

interface JobInterface
{
    public function toArray() : array;
    public function fillFromArray(array $data);
    public function isValid() : bool;
}