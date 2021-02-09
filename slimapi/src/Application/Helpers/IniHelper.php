<?php

declare(strict_types=1);

namespace App\Application\Helpers;

class IniHelper
{

    public static function setHeavyScriptValues()
    {
        // no max execution time
        @set_time_limit(0);

        // default to 1024MB unless current value is higher
        $current_memory_limit = @ini_get('memory_limit');
        if ($current_memory_limit) {
            $memory_limit = self::return_bytes($current_memory_limit);
            if ($memory_limit === -1 || $memory_limit < (1024 * 1024 * 1024)) {
                @ini_set("memory_limit", "1024M");
            }
        }
    }


    /**
     * Converts shorthand memory notation value to bytes
     * From http://php.net/manual/en/function.ini-get.php
     *
     * @param $val Memory size shorthand notation string
     */
    private static function return_bytes(string $val): int
    {
        $val = trim($val);

        // -1 is unlimited
        if ($val === '-1') {
            return -1;
        }

        $last = strtolower($val[strlen($val) - 1]);
        $val  = substr($val, 0, -1);
        switch ($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }
}