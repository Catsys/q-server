<?php

namespace QServer\Commands;

use ReflectionClass;

class CommandFactory
{
    public static function create($key) {
        $dir = __DIR__;
        $files = glob($dir.'/*.php');

        foreach ($files as $file) {
            try {
                $classname = 'QServer\\Commands\\'.pathinfo(basename($file), PATHINFO_FILENAME);
                if (!class_exists($classname)) {
                    continue;
                }
                $class = new \ReflectionClass($classname);

                if (!$signature = $class->getStaticPropertyValue('signature')) {
                   continue;
                }

                if ($signature === $key) {
                    return $class->newInstance();
                }

            }
            catch (\Exception $e) {}
        }

        return new HelpCommand();
    }

}