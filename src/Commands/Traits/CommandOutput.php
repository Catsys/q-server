<?php

namespace QServer\Commands\Traits;

trait CommandOutput
{
    protected function info($text) {
        $this->cliMessage("\033[36m {$text} \033[0m\n");
    }
    protected function error($text) {
        $this->cliMessage("\033[31m {$text} \033[0m\n");
    }
    protected function success($text) {
        $this->cliMessage("\033[32m {$text} \033[0m\n");
    }

    protected function cliMessage($text) {
        $text = date('h:i:s').'-'.$text;
        echo $text;
    }

}
