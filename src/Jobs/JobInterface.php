<?php

namespace QServer\Jobs;

interface JobInterface
{

    /**
     * Convert Job data to array for serialization to storage
     *
     * @return array data for serialization
     */
    public function toArray() : array;

    /**
     * Fill model struct from array (from storage)
     *
     * @param array $data
     * @return void
     */
    public function fillFromArray(array $data);

    /**
     * Validation filled data
     *
     * @return bool
     */
    public function isValid() : bool;
}