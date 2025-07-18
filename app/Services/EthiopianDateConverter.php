<?php

namespace App\Services;

class EthiopianDateConverter
{
    private const JD_EPOCH_OFFSET_AMETE_MIHRET = 1723856;
    private const MONTH_NAMES = [
        'Meskerem',
        'Tikimit',
        'Hidar',
        'Tahesas',
        'Tir',
        'Yekatit',
        'Megabit',
        'Miazia',
        'Genbot',
        'Sene',
        'Hamle',
        'Nehase',
        'Pagume'
    ];
    private const DAY_NAMES = ['Ehud', 'Segno', 'Maksegno', 'Erob', 'Hamus', 'Arb', 'Kidame'];

    public function gregorianToEthiopian($date = null): array
    {
        $gregorianDate = $this->parseDate($date);
        $julianDay = $this->gregorianToJulian(
            (int)$gregorianDate->format('Y'),
            (int)$gregorianDate->format('m'),
            (int)$gregorianDate->format('d')
        );

        return $this->julianToEthiopian($julianDay);
    }

    private function parseDate($date): \DateTimeInterface
    {
        if ($date === null) return new \DateTime();
        if ($date instanceof \DateTimeInterface) return $date;

        try {
            return new \DateTime($date);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Invalid date format. Expected Y-m-d or DateTime object.");
        }
    }

    private function gregorianToJulian(int $year, int $month, int $day): int
    {
        $a = (int)((14 - $month) / 12);
        $y = $year + 4800 - $a;
        $m = $month + 12 * $a - 3;

        return $day + (int)((153 * $m + 2) / 5) + 365 * $y
            + (int)($y / 4) - (int)($y / 100) + (int)($y / 400) - 32045;
    }

    private function julianToEthiopian(int $julianDay): array
    {
        // Calculate days since Ethiopian epoch
        $daysSinceEpoch = $julianDay - self::JD_EPOCH_OFFSET_AMETE_MIHRET;

        // Calculate Ethiopian year
        $year = 4 * (int)floor($daysSinceEpoch / 1461) + 1;
        $dayOfYear = ($daysSinceEpoch % 1461) % 365 + 1;

        // Handle leap year (Pagume has 6 days instead of 5)
        $isLeapYear = ($year % 4) === 3;

        // Calculate month and day
        if ($dayOfYear <= 360) {
            $month = (int)ceil($dayOfYear / 30);
            $day = $dayOfYear % 30;
            $day = $day === 0 ? 30 : $day;
        } else {
            $month = 13;
            $day = $dayOfYear - 360;
        }

        // FINALLY CORRECT Day of week calculation
        // The Ethiopian week starts with Ehud (Sunday) = 0
        // We need to add 2 days offset from Julian day to align with Ethiopian week
        $dayOfWeek = ($daysSinceEpoch + 2) % 7;

        return [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'monthName' => self::MONTH_NAMES[$month - 1] ?? '',
            'dayOfWeek' => $dayOfWeek,
            'dayName' => self::DAY_NAMES[$dayOfWeek] ?? '',
            'isLeapYear' => $isLeapYear,
        ];
    }

}
