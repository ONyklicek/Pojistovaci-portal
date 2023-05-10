<?php
/**
 * Class Application
 *
 * @autor   Ondrej Nyklicek <ondrejnykicek@icloud.com>
 * @project www
 * @IDE     PhpStorm
 */

namespace App\Core;

class ArrayHelper
{
    /**
     * @param string $prefix
     * @param array $data
     * @return array
     */
    public static function arrayPrefixed(string $prefix, array $data): array
    {
        $prefixArray[$prefix] = [];
        foreach ($data as $key => $value){
                $prefixArray[$prefix][$key] = $value;
        }

        return $prefixArray;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public static function arrayCreate(string $key, mixed $value): array
    {
        return [$key => $value];
    }
}
