<?php

namespace QServer\Jobs;

class JobStruct implements JobInterface {
    public string $id;
    public string $cmd;
    public string $comment = '';
    public int $delay = 0;
    public int $tries = 1;
    public int $counter_tries = 1;
    public int $created;
    public int $tries_delay = 180;

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

    public function fillFromArray(array $data) {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function isValid() : bool {
        return true;
    }
}