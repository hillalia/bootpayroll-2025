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
        $start = Carbon::create(now()->year, 1, 30); // 30 Jan

        for ($i = 0; $i < 12; $i++) {
            $startDate = $start->copy()->addMonths($i);
            $endDate = $startDate->copy()->addMonth()->subDay(); // 29 bulan berikutnya

            SalaryPeriod::firstOrCreate([
                'name' => $startDate->format('F Y'),
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ]);
        }
    }
}
