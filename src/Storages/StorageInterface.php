<?php

namespace QServer\Storages;

use QServer\Jobs\JobInterface;

interface StorageInterface {
    public function getRow();
    public function delete($id);
    public function save(array $data) : bool;
}