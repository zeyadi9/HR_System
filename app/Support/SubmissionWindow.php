<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class SubmissionWindow
{
    public static function today(): Carbon
    {
        return Carbon::today();
    }

    public static function earliestAllowedDate(): Carbon
    {
        $yesterday = static::today()->copy()->subDay();
        $periodStart = PayrollPeriod::currentStart();
        
        // If yesterday belongs to a different (previous) period, 
        // we lock it once the new period starts (on the 26th).
        return $yesterday->lt($periodStart) ? $periodStart : $yesterday;
    }

    public static function latestAllowedDate(): Carbon
    {
        return static::today();
    }

    public static function assertDateWithinAllowedWindow(string $date, string $label = 'Date'): void
    {
        $submittedDate = Carbon::parse($date)->startOfDay();
        $earliest = static::earliestAllowedDate()->startOfDay();
        $latest = static::latestAllowedDate()->startOfDay();

        if ($submittedDate->lt($earliest) || $submittedDate->gt($latest)) {
            $rangeText = $earliest->isSameDay($latest) 
                ? 'اليوم فقط' 
                : 'اليوم أو أمس فقط';

            throw ValidationException::withMessages([
                'date' => sprintf(
                    'تاريخ الـ %s يجب أن يكون %s (%s إلى %s).',
                    $label,
                    $rangeText,
                    $earliest->toDateString(),
                    $latest->toDateString()
                ),
            ]);
        }
    }

    public static function bounds(): array
    {
        return [
            'min' => static::earliestAllowedDate()->toDateString(),
            'max' => static::latestAllowedDate()->toDateString(),
        ];
    }
}
