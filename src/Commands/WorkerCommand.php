<?php

namespace QServer\Commands;


use QServer\Exceptions\ArgumentsNotValidExceptions;
use QServer\Exceptions\OtherExceptions;
use QServer\Jobs\JobStruct;
use QServer\Storages\StorageFactory;
use QServer\Commands\Traits\CommandOutput;
use QServer\Exceptions\AlreadyRunningException;

class WorkerCommand implements CommandInterface
{
    use CommandOutput;
    public static $signature = 'worker';

    public function run(array $data = [])
    {
        $data = $this->prepareData($data);
        $this->silentMode = !empty($data['silent-mode']);
        if (($data['single-mode'] ?? 'false') !== 'false') {
            $dir = __PROJECT_ROOT__.'/data';
            @mkdir($dir);
            $lockFile = $dir.'/worker_lock';
            if (file_exists($lockFile)) {
                $created = filemtime($lockFile);
                if((time() - $created) < 10) {
                    throw new AlreadyRunningException();
                }
            }
        }

        $storage = StorageFactory::create();
        $this->info('start worker');

        while (true) {
            
            if (!empty($lockFile)){
                touch($lockFile);
            }

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

            $this->info('run job '.$job->id.(!empty($job->comment) ? ' - ' . $job->comment : null) );
            
            if ($job->counter_tries > 1) {
                $this->info('tries '.$job->counter_tries);
            }

            $storage->delete($job->id);
            \exec($job->cmd, $res, $resultCode);


            if ($resultCode) {
                if (count($res)) {
                    $this->error(implode("\n >", $res));
                }
                
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
            }
            
            $key = str_replace('--', '', $key ?? $arg);
            $newData[$key] = $value ?? null;
        }
        return $newData;
    }

}