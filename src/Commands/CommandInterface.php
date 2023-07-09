<?php

namespace QServer\Commands;

/**
 * Executable q-server command interface
 */
interface CommandInterface
{
    /**
     * Runtime method
     *
     * @param array $data
     * @return mixed
     */
    public function run(array $data = []);

    /**
     * Validation options
     *
     * @param array $data
     * @return bool
     */
    public function isValid(array $data = []):bool;
}