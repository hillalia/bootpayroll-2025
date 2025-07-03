<?php

namespace Database\Seeders;

use App\Models\SalaryDeduction;
use App\Models\SalaryPeriod;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DivisionSeeder::class,
            UserSeeder::class,
            SalaryPeriodSeeder::class,
            SalaryDeductionSeeder::class,
            MasterSalarySeeder::class,
        ]);
    }
}
