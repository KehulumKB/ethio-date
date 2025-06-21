<?php

namespace App\Services;

class EthiopianDateConverter
{
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
        $date = $this->parseDate($date);
        $gy = (int)$date->format('Y');
        $gm = (int)$date->format('m');
        $gd = (int)$date->format('d');

        // Determine Ethiopian year based on Ethiopian New Year (Sept 11/12)
        $newYearGregorian = new \DateTime("$gy-09-11");
        if ($this->isGregorianLeapYear($gy)) {
            $newYearGregorian = new \DateTime("$gy-09-12");
        }

        if ($date < $newYearGregorian) {
            $ey = $gy - 8;
            $newYearGregorian->modify('-1 year');
        } else {
            $ey = $gy - 7;
        }

        $daysSinceNewYear = $date->diff($newYearGregorian)->days;

        $em = (int)($daysSinceNewYear / 30) + 1;
        $ed = ($daysSinceNewYear % 30) + 1;

        // Accurate Ethiopian day of the week using Julian Day Number
        $jd = $this->gregorianToJulian($gy, $gm, $gd);
        $ethiopianDayOfWeek = (int)(($jd + 1) % 7); // 0 = Ehud

        return [
            'year' => $ey,
            'month' => $em,
            'day' => $ed,
            'monthName' => self::MONTH_NAMES[$em - 1] ?? '',
            'dayOfWeek' => $ethiopianDayOfWeek,
            'dayName' => self::DAY_NAMES[$ethiopianDayOfWeek],
            'isLeapYear' => $ey % 4 === 3,
        ];
    }

    public function ethiopianToGregorian(int $ey, int $em, int $ed): array
    {
        // Ethiopian New Year in Gregorian calendar (starting from year 8)
        $gy = $ey + 7;
        $newYear = new \DateTime("$gy-09-11");

        if ($this->isGregorianLeapYear($gy + 1)) {
            $newYear = new \DateTime("$gy-09-12");
        }

        $days = ($em - 1) * 30 + ($ed - 1); // days since Ethiopian New Year
        $newYear->modify("+$days days");

        return [
            'year' => (int)$newYear->format('Y'),
            'month' => (int)$newYear->format('m'),
            'day' => (int)$newYear->format('d'),
            'dayName' => $newYear->format('l'),
        ];
    }


    private function isGregorianLeapYear(int $year): bool
    {
        return ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0);
    }

    private function gregorianToJulian(int $year, int $month, int $day): int
    {
        $a = (int)((14 - $month) / 12);
        $y = $year + 4800 - $a;
        $m = $month + 12 * $a - 3;

        return $day + (int)((153 * $m + 2) / 5) + 365 * $y
            + (int)($y / 4) - (int)($y / 100) + (int)($y / 400) - 32045;
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

    public function verifyConversions(): bool
    {
        $testCases = [
            ['gregorian' => '2023-04-11', 'expected' => ['year' => 2015, 'month' => 8, 'day' => 3, 'dayName' => 'Hamus']],
            ['gregorian' => '2025-06-21', 'expected' => ['year' => 2017, 'month' => 10, 'day' => 14, 'dayName' => 'Kidame']],
            ['gregorian' => '2023-09-11', 'expected' => ['year' => 2015, 'month' => 13, 'day' => 6, 'dayName' => 'Arb']],
            ['gregorian' => '2023-09-12', 'expected' => ['year' => 2016, 'month' => 1, 'day' => 1, 'dayName' => 'Ehud']],
        ];

        foreach ($testCases as $case) {
            $result = $this->gregorianToEthiopian($case['gregorian']);

            foreach ($case['expected'] as $key => $value) {
                if ($result[$key] != $value) {
                    throw new \RuntimeException(sprintf(
                        "Conversion failed for %s! Expected %s %d %d (%s), got %s %d %d (%s)",
                        $case['gregorian'],
                        self::MONTH_NAMES[$case['expected']['month'] - 1] ?? '',
                        $case['expected']['day'],
                        $case['expected']['year'],
                        $case['expected']['dayName'],
                        $result['monthName'],
                        $result['day'],
                        $result['year'],
                        $result['dayName']
                    ));
                }
            }
        }

        return true;
    }

    public function formatEthiopianDate(array $ethiopicDate): string
    {
        if (empty($ethiopicDate)) return '';

        return sprintf(
            '%s %d, %d (%s)',
            $ethiopicDate['monthName'] ?? '',
            $ethiopicDate['day'] ?? 0,
            $ethiopicDate['year'] ?? 0,
            $ethiopicDate['dayName'] ?? ''
        );
    }

    public function formatGregorianDate(array $gcDate): string
    {
        if (empty($gcDate)) return '';

        return sprintf(
            '%04d-%02d-%02d (%s)',
            $gcDate['year'] ?? 0,
            $gcDate['month'] ?? 0,
            $gcDate['day'] ?? 0,
            $gcDate['dayName'] ?? ''
        );
    }
}
