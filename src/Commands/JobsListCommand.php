<?php

namespace QServer\Commands;

use QServer\Commands\Traits\CommandOutput;
use QServer\Jobs\JobStruct;
use QServer\Storages\StorageFactory;

/**
 * Show a list of queued jobs
 */
class JobsListCommand implements CommandInterface
{
    use CommandOutput;
    /**
     * Command signature for call in cli
     *
     * @var string
     */
    public static $signature = 'list';

    /**
     * @inheritDoc
     */
    public function run($data = [])
    {
        $storage = StorageFactory::create();
        $allRows = $storage->getAllRows();

        if (!$allRows) {
            return 'Task queue is empty';
        }

        $jobs = [];
        foreach ($allRows as $row) {
            $job = (new JobStruct());
            $job->fillFromArray($row);
            $jobs[] = $job;
        }

        $format = "|--- ID: %s \n| Comment: \033[36m %s \033[0m \n| Command: %s \n| Tries: %s \n| Last Run: %s \n| Next Run: %s \n|--\n";

        foreach ($jobs as $job) {
            printf($format,
                $job->id,
                $job->comment,
                $job->cmd,
                $job->counter_tries . '/' . $job->tries,
                date('Y-m-d H:i:s', $job->created),
                date('Y-m-d H:i:s', $job->created + (($job->counter_tries + 1) * $job->tries_delay) + $job->delay)
            );
        }

    }

    /**
     * @inheritDoc
     */
    public function isValid($data = []) : bool
    {
        return true;
    }
}