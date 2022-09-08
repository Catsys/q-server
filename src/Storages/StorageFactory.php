<?php

namespace QServer\Storages;

use QServer\Exceptions\StorageNotFoundExceptions;

class StorageFactory
{
    public static function create($driver = 'file') : StorageInterface {
        switch ($driver) {
            case 'file': {
                return new StorageFile();
            }
            default: {
                throw new StorageNotFoundExceptions();
            }
        }
    }
}