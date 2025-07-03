<?php

namespace App\Services;

use App\Models\SalaryPeriod;
use Carbon\Carbon;

class SalaryPeriodGenerator
{
    public function generateForYear(int $year): int
    {
        $generated = 0;

        for ($i = 0; $i < 12; $i++) {
            $base = Carbon::create($year, 1, 1)->addMonths($i);

            $startDate = $base->copy()->day(min(30, $base->daysInMonth));

            $nextMonthBase = $base->copy()->addMonth();
            $nextMonth = $nextMonthBase->month;
            $nextYear = $nextMonthBase->year;

            $daysInNextMonth = Carbon::create($nextYear, $nextMonth, 1)->daysInMonth;
            $endDay = ($nextMonth === 2) ? max(1, $daysInNextMonth - 2) : min(29, $daysInNextMonth);
            $endDate = Carbon::create($nextYear, $nextMonth, $endDay);

            $exists = SalaryPeriod::where('start_date', $startDate->toDateString())->exists();
            if (!$exists) {
                SalaryPeriod::create([
                    'name' => $startDate->format('F Y'),
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                ]);
                $generated++;
            }
        }

        return $generated;
    }
}
