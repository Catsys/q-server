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
     * Get path to logfile
     */
    public function getLogFilePath() {
        return __DATA_DIR__ . '/log_'.date('Y_m_d').'.log';
    }

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
        $prefix = date('m.d H:i:s').'-';
        if ($this->silentMode) {
            file_put_contents($this->getLogFilePath(), $prefix.($logText ?? $text)."\n", FILE_APPEND);
        }
        else {
            echo $prefix.$text;
        }
    }

}
