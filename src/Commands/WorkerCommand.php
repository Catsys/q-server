<?php

namespace QServer\Commands;


use QServer\Commands\Traits\DataOptionsHelper;
use QServer\Exceptions\OtherExceptions;
use QServer\Jobs\JobStruct;
use QServer\Storages\StorageFactory;
use QServer\Commands\Traits\CommandOutput;
use QServer\Exceptions\AlreadyRunningException;

class WorkerCommand implements CommandInterface
{
    use CommandOutput;
    use DataOptionsHelper;

    /**
     * Command signature for call in cli
     *
     * @var string
     */
    public static $signature = 'worker';

    /**
     * @inheritDoc
     */
    public function run(array $data = [])
    {
        $data = $this->prepareData($data);
        $this->silentMode = !empty($data['silent-mode']);
        $skipNextSleep = true; // Once skip sleep
        $storage = StorageFactory::create();
        $sleepDelay = $data['sleep'] ?? 3;
        $dataDir = __PROJECT_ROOT__.'/data';
        @mkdir($dataDir);
        if (($data['single-mode'] ?? 'false') !== 'false') {
            $lockFile = $dataDir.'/worker_lock';
            if (file_exists($lockFile)) {
                $created = filemtime($lockFile);
                if((time() - $created) < (10 + $sleepDelay)) {
                    throw new AlreadyRunningException();
                }
            }
        }
        $stopFile = $dataDir.'/'.StopWorkerCommand::WORKER_STOP_FILENAME;
        if (file_exists($stopFile)) {
            unlink($stopFile);
        }

        $this->info('start worker');

        while (true) {
            // Once skip sleep
            if ($skipNextSleep) {
                $skipNextSleep = false;
            }
            else {
                // Sleep between job search
                \sleep($sleepDelay);
            }

            if (file_exists($stopFile)) {
                $this->error('The user has ordered an exit');
                exit;
            }
            // Update lock file
            if (!empty($lockFile)){
                touch($lockFile);
            }
            // Get one row from storage
            if (!$data = $storage->getRow()) {
                continue;
            }

            // Job creating
            $job = new JobStruct();
            $job->fillFromArray($data);

            if (!$job->isValid()) {
                $this->error('Job have incorrect structure');

                if (!empty($job->id)) {
                    $this->error("Deleting job {$job->id} from storage");
                    $storage->delete($job->id);
                }
                else {
                    $this->error("The job cannot be deleted because the job ID is empty. You must remove it manually. Maybe storage will take care of that.");
                }

                continue;
            }

            $delay = 0;

            // Check delay before first run
            if ($job->counter_tries <= 1 && $job->delay) {
                $delay = $job->delay;
            }
            // Check delay between tries
            elseif ($job->counter_tries > 1 && $job->tries_delay) {
                $delay = $job->tries_delay;
            }

            // Early run
            if ($delay && ($job->created + $delay) > time()) {
                $skipNextSleep = true;
                continue;
            }

            $this->info('run job '.$job->id.(!empty($job->comment) ? ' - ' . $job->comment : null) );
            
            if ($job->counter_tries > 1) {
                $this->info('tries '.$job->counter_tries);
            }
            // Before running, we deleting row from storage
            $storage->delete($job->id);

            \exec($job->cmd, $res, $resultCode);

            // If it fails, then put the task back in the queue
            if ($resultCode) {
                if (count($res)) {
                    $this->error(implode("\n >", $res));
                }

                // Attempts are over, then delete the task
                if ($job->counter_tries >= $job->tries) {
                    $this->error('fail');
                    continue;
                }
                $this->error('to retry');
                // Let's record the attempt and back to the queue
                $storage = StorageFactory::create();
                $job->counter_tries++;
                $job->created = time();
                $storage->save($job->toArray());     
                continue;               
            }
            
            $this->success('success');
            
        }
    }

    /**
     * @inheritDoc
     */
    public function isValid(array $data = []) : bool
    {
        return true;
    }


}