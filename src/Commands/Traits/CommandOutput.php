<?php

namespace QServer\Commands\Traits;

/**
 * Trait for help showing message in cli interface ot log
 */
trait CommandOutput
{

    /**
     * Save output to log. Don't show in console
     *
     * @var bool
     */
    protected $silentMode = false;

    /**
     * Show info messages in turquoise color
     *
     * @param $text
     * @return void
     */
    protected function info($text) {
        $this->cliMessage("\033[36m {$text} \033[0m\n", "INFO: {$text}");
    }

    /**
     * Show error messages in red color
     *
     * @param $text
     * @return void
     */
    protected function error($text) {
        $this->cliMessage("\033[31m {$text} \033[0m\n", "ERROR: {$text}");
    }

    /**
     * Show success messages in green color
     *
     * @param $text
     * @return void
     */
    protected function success($text) {
        $this->cliMessage("\033[32m {$text} \033[0m\n", "DONE: {$text}");
    }

    /**
     * Logic for showing message
     *
     * @param $text
     * @param $logText
     * @return void
     */
    protected function cliMessage($text, $logText = null) {
        $prefix = date('H:i:s').'-';
        if ($this->silentMode) {
            $logFile = __PROJECT_ROOT__.'/data/log_'.date('Y_m_d').'.log';
            file_put_contents($logFile, $prefix.($logText ?? $text)."\n", FILE_APPEND);
        }
        else {
            echo $prefix.$text;
        }
    }

}
