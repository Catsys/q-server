<?php

namespace QServer\Commands\Traits;

trait CommandOutput
{
    protected $silentMode = false;
    protected function info($text) {
        $this->cliMessage("\033[36m {$text} \033[0m\n", "INFO: {$text}");
    }
    protected function error($text) {
        $this->cliMessage("\033[31m {$text} \033[0m\n", "ERROR: {$text}");
    }
    protected function success($text) {
        $this->cliMessage("\033[32m {$text} \033[0m\n", $text);
    }

    protected function cliMessage($text, $logText = null) {
        $prefix = date('h:i:s').'-';

        if ($this->silentMode) {
            $logFile = __PROJECT_ROOT__.'/data/log_'.date('Y_m_d').'.log';
            file_put_contents($logFile, $prefix.($logText ?? $text)."\n", FILE_APPEND);
        }
        else {
            echo $prefix.$text;
        }
    }

}
