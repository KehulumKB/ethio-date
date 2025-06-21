<?php

if (!function_exists('to_ethiopian_date')) {
    function to_ethiopian($date = null, string $format = 'd/m/Y'): string
    {
        $converter = app('ethiopian-date');
        $ethiopianDate = $converter->gregorianToEthiopian($date);
        return $converter->formatEthiopian($ethiopianDate, $format);
    }
}
