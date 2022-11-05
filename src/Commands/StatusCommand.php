<?php

namespace QServer\Commands;

use QServer\Commands\Traits\CommandOutput;
use QServer\Storages\StorageFactory;

/**
 * Show worker status
 */
class StatusCommand implements CommandInterface
{
    use CommandOutput;
    /**
     * Command signature for call in cli
     *
     * @var string
     */
    public static $signature = 'status';

    /**
     * @inheritDoc
     */
    public function run($data = [])
    {
        $tickFile = __DATA_DIR__.'/worker_tick';
        $storage = StorageFactory::create();
        $count = $storage->countRows();
        $output = "Tasks in the queue: {$count}" . PHP_EOL;

        if (file_exists($tickFile)) {
            $createdTs = filemtime($tickFile);
            // label if the delay is more than 30 minutes
            if (time() - $createdTs > 1800) {
                $output .=  '❗ ';
            }

            $output .= 'Last worker activity: '.date('Y-m-d H:i:s', $createdTs);

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