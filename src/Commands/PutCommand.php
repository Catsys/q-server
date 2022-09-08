<?php

namespace QServer\Commands;


use QServer\Exceptions\ArgumentsNotValidExceptions;
use QServer\Exceptions\OtherExceptions;
use QServer\Jobs\JobStruct;
use QServer\Storages\StorageFactory;

class PutCommand implements CommandInterface
{
    public static $signature = 'put';

    public function run(array $data = [])
    {
        $data = $this->prepareData($data);
        if(!$this->isValid($data)) {
            throw new ArgumentsNotValidExceptions();
        }

        if (empty($data['id'])) {
            $data['id'] = str_replace('.','', uniqid('', true));
        }

        $job = new JobStruct();
        $job->fillFromArray($data);
        $job->created = time();

        $storage = StorageFactory::create();

        if ($storage->save($job->toArray())) {
            return $job->id;
        }

        throw new OtherExceptions();
    }

    private function prepareData($data) {
        $newData = [];
        foreach ($data as $arg) {
            if (strpos($arg, '=') !== false) {
                [$key, $value] = explode('=', $arg);
                $key = str_replace('--', '', $key);
                $newData[$key] = $value;
            }
        }
        return $newData;
    }

    public function isValid(array $data = []) : bool
    {
        return count(array_diff_key(array_flip(['cmd']), $data)) === 0;
    }
}