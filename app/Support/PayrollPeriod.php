<?php

namespace App\Support;

use Carbon\Carbon;

class PayrollPeriod
{
    public static function current(?Carbon $referenceDate = null): array
    {
        $referenceDate ??= Carbon::today();

        return self::fromDate($referenceDate);
    }

    public static function fromRequest(?string $periodStart = null, ?Carbon $fallbackReferenceDate = null): array
    {
        if (blank($periodStart)) {
            return self::current($fallbackReferenceDate);
        }

        try {
            $requestedDate = Carbon::parse($periodStart);
        } catch (\Throwable $e) {
            return self::current($fallbackReferenceDate);
        }

        return self::fromDate($requestedDate);
    }

    public static function fromDate(Carbon $date): array
    {
        $periodStart = self::normalizeStart($date);
        $periodEnd = $periodStart->copy()->addMonthNoOverflow()->day(25)->endOfDay();
        $currentPeriod = self::currentStart();

        return [
            'start' => $periodStart,
            'end' => $periodEnd,
            'previous_start' => $periodStart->copy()->subMonthNoOverflow()->day(26)->startOfDay(),
            'next_start' => $periodStart->copy()->addMonthNoOverflow()->day(26)->startOfDay(),
            'label' => $periodStart->format('d M Y') . ' → ' . $periodEnd->format('d M Y'),
            'slug' => $periodStart->format('Y-m-d'),
            'is_current' => $periodStart->isSameDay($currentPeriod),
        ];
    }

    public static function currentStart(?Carbon $referenceDate = null): Carbon
    {
        $referenceDate ??= Carbon::today();

        return self::normalizeStart($referenceDate);
    }

    protected static function normalizeStart(Carbon $date): Carbon
    {
        if ($date->day >= 26) {
            return $date->copy()->day(26)->startOfDay();
        }

        return $date->copy()->subMonthNoOverflow()->day(26)->startOfDay();
    }
}
