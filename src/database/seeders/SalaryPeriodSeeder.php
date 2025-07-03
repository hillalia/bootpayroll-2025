<?php

namespace Database\Seeders;

use App\Models\SalaryPeriod;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalaryPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $year = now()->year;

        for ($i = 0; $i < 12; $i++) {
            // Bulan dasar
            $base = Carbon::create($year, 1, 1)->addMonths($i);

            // Tanggal mulai = 30 atau akhir bulan jika tidak ada 30
            $startDate = $base->copy()->day(min(30, $base->daysInMonth));

            // Hitung bulan dan tahun berikutnya berdasarkan base (bukan dari startDate)
            $nextMonthBase = $base->copy()->addMonth();
            $nextMonth = $nextMonthBase->month;
            $nextYear = $nextMonthBase->year;

            // Hitung end_date manual
            $daysInNextMonth = Carbon::create($nextYear, $nextMonth, 1)->daysInMonth;

            if ($nextMonth === 2) {
                // Februari, kurangi 2 hari
                $endDay = max(1, $daysInNextMonth - 2);
            } else {
                // Selain Februari, pakai tanggal 29 atau akhir bulan
                $endDay = min(29, $daysInNextMonth);
            }

            $endDate = Carbon::create($nextYear, $nextMonth, $endDay);

            SalaryPeriod::firstOrCreate([
                'name' => $startDate->format('F Y'),
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ]);
        }
    }
}
