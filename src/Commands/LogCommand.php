<?php

namespace QServer\Commands;

use QServer\Commands\Traits\CommandOutput;
use QServer\Commands\Traits\DataOptionsHelper;

/**
 * Show log
 */
class LogCommand implements CommandInterface
{
    use CommandOutput;
    use DataOptionsHelper;

    /**
     * Command signature for call in cli
     *
     * @var string
     */
    public static $signature = 'log';

    /**
     * @inheritDoc
     */
    public function run($data = [])
    {
        $data = $this->prepareData($data);
        $logPath = $this->getLogFilePath();
        $lines = $data['lines'] ?? $data['r'] ?? $data['n'] ?? 20;
        $lines++;

        return 'file path: ' .$logPath . PHP_EOL . PHP_EOL . $this->tail($logPath, $lines);
    }

    function tail($filename, $n) {
        $file = fopen($filename, "r");
        $pos = -2;
        $eof = false;
        $lines = array();
        while (count($lines) < $n) {
            $char = '';
            while ($char !== "\n") {
                if (fseek($file, $pos, SEEK_END) === -1) {
                    $eof = true;
                    break;
                }
                $char = fgetc($file);
                $pos--;
            }
            $lines[] = fgets($file);
            if ($eof) {
                break;
            }
        }
        fclose($file);
        return implode(null, array_reverse($lines));
    }

    /**
     * @inheritDoc
     */
    public function isValid($data = []) : bool
    {
        return true;
    }
}