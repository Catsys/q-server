<?php

namespace QServer\Commands\Traits;

/**
 * Trait for help workings to input options data
 */
trait DataOptionsHelper
{

    /**
     * Prepare array key => value for input data
     * @param $data array data array from input
     *
     * @return array key => value
     */
    protected function prepareData(array $data): array
    {
        $newData = [];
        foreach ($data as $arg) {
            if (strpos($arg, '=') !== false) {
                [$key, $value] = explode('=', $arg);
            }

            $key = str_replace('--', '', $key ?? $arg);
            $newData[$key] = $value ?? null;
        }
        return $newData;
    }

}
