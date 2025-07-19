<?php

if (!function_exists('to_ethiopian')) {
    function to_ethiopian($date = null, string $format = 'd/m/Y'): string
    {
        $converter = app('date_converter');
        $ethiopianDate = $converter->gregorianToEthiopian($date);
        return $converter->formatEthiopian($ethiopianDate, $format);
    }
}

if (!function_exists('to_gregorian')) {
    function to_gregorian(int $year, int $month, int $day, string $format = 'Y-m-d'): string
    {
        $converter = app('date_converter');

        try {
            $date = $converter->ethiopianToGregorian($year, $month, $day);
            return $date->format($format);
        } catch (\Exception $e) {
            return "Invalid date";
        }
    }
}
