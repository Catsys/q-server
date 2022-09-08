<?php
define('__START_SCRIPT__', __FILE__);

require_once 'boot.php';

use \QServer\Commands\CommandFactory;
use QServer\Exceptions\QServerErrorInterface;

if (!empty($argv[1])) {
    if ($cmd = CommandFactory::create($argv[1])) {
        try {
            echo $cmd->run(array_slice($argv, 2)) . PHP_EOL;
        }
        catch (QServerErrorInterface $e) {
            echo "\033[31m ERROR: ".$e->getMessage()." \033[0m\n";
        }
        exit;
    }
}
$cmd = CommandFactory::create('help');
echo $cmd->run();