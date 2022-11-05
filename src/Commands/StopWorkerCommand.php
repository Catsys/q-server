<?php

namespace QServer\Commands;


use QServer\Commands\Traits\DataOptionsHelper;
use QServer\Commands\Traits\CommandOutput;

/**
 * Stopped all running workers
 */
class StopWorkerCommand implements CommandInterface
{
    use CommandOutput;

    public const WORKER_STOP_FILENAME = 'workers_stop';

    /**
     * Command signature for call in cli
     *
     * @var string
     */
    public static $signature = 'stop';

    /**
     * @inheritDoc
     */
    public function run(array $data = [])
    {
        @mkdir(__DATA_DIR__);
        touch(__DATA_DIR__.'/'.self::WORKER_STOP_FILENAME);
        $this->success('success');
    }

    /**
     * @inheritDoc
     */
    public function isValid(array $data = []) : bool
    {
        return true;
    }


}