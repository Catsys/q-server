<?php

namespace QServer\Commands;


use QServer\Exceptions\ArgumentsNotValidExceptions;
use QServer\Exceptions\OtherExceptions;
use QServer\Jobs\JobStruct;
use QServer\Storages\StorageFactory;
use QServer\Commands\Traits\CommandOutput;

class WorkerCommand implements CommandInterface
{
    use CommandOutput;
    public static $signature = 'worker';

    public function run(array $data = [])
    {

        $storage = StorageFactory::create();
        $this->info('start worker');

        while (true) {
            \sleep(1);

            if (!$data = $storage->getRow()) {
                continue;
            }

            $job = new JobStruct();
            $job->fillFromArray($data);
            
            $delay = 0;
            
            if ($job->counter_tries <= 1 && $job->delay ) {
                $delay = $job->delay;
            }
            elseif ($job->counter_tries > 1 && $job->tries_delay) {
                $delay = $job->tries_delay;
            }

            if ($delay && ($job->created + $delay) > time()) {
                continue;
            }

            $this->info('run job '.$job->id.' - '.$job->comment);
            
            if ($job->counter_tries > 1) {
                $this->info('tries '.$job->counter_tries);
            }

            $storage->delete($job->id);
            \exec($job->cmd, $res, $resultCode);


            if ($resultCode) {
                if ($job->counter_tries >= $job->tries) {
                    $this->error('fail');
                    continue;
                }
                $this->error('to retry');
                $storage = StorageFactory::create();
                $job->counter_tries++;
                $job->created = time();
                $storage->save($job->toArray());     
                continue;               
            }
            $this->success('success');
            
        }
        
        throw new OtherExceptions();
    }


    public function isValid(array $data = []) : bool
    {
        return true;
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

}