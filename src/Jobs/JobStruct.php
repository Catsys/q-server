<?php

namespace QServer\Jobs;

class JobStruct implements JobInterface {


    /** Unique ID in storage
     * @var string
     */
    public string $id;
    /**
     * Command for execute
     * @var string
     */
    public string $cmd;
    /**
     * Comment for logs and other (optional)
     * @var string
     */
    public string $comment = '';
    /**
     * Delay before first run in seconds
     * @var int
     */
    public int $delay = 0;
    /**
     * Max number run tries
     * @var int
     */
    public int $tries = 1;
    /**
     * System counter left tries. Grows with each iteration
     * @var int
     */
    public int $counter_tries = 1;
    /**
     * Created timestamp for sorting in storage
     * @var int
     */
    public int $created;
    /**
     * Delay between tries in seconds
     * @var int
     */
    public int $tries_delay = 180;

    /**
     * @inheritdoc
     */
    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'cmd' => $this->cmd,
            'comment' => $this->comment,
            'delay' => $this->delay,
            'tries' => $this->tries,
            'tries_delay' => $this->tries_delay,
            'counter_tries' => $this->counter_tries,
            'created' => $this->created,
        ];
    }

    /**
     * @inheritdoc
     */
    public function fillFromArray(array $data) {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function isValid() : bool {
        return !empty($this->cmd)
            && !empty($this->id);
    }
}