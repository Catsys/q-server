<?php

namespace QServer\Commands;

use QServer\Jobs\JobStruct;
use QServer\Storages\StorageFactory;

/**
 * Show a list of queued jobs
 */
class JobsListCommand implements CommandInterface
{
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

        $output = null;
        foreach ($jobs as $job) {
            $nextRun = $job->created + $job->tries_delay;
            $output .=
                sprintf("|--- ID: %s \n| Comment: \033[36m %s \033[0m \n| Command: %s \n| Tries: %s \n| Last Run: %s \n| Next Run: %s \n|--\n",
                    $job->id,
                    $job->comment,
                    $job->cmd,
                    $job->counter_tries . '/' . $job->tries,
                    date('Y-m-d H:i:s', $job->created),
                    date('Y-m-d H:i:s', $nextRun) . ( ($nextRun + 180) < time() ? '❗ ' : null )
                );
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function isValid($data = []) : bool
    {
        return true;
    }
}