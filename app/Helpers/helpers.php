<?php

use Carbon\Carbon;

if (! function_exists('enum')) {
    function enum($className) {
        return new \App\Enums\EnumHelper($className);
    }
}

/**
 * @returns string
 */
if (!function_exists('default_date_format')) {
    function default_date_format_from_string($date) {
        if (!is_string($date)) {
            throw new \App\Exceptions\ShouldBeStringException('Date should be a string');
        }
        return Carbon::createFromTimeString( $date)->format('Y/m/d h:i a');
    }
}


if (!function_exists('formatSizeUnits')) {
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
