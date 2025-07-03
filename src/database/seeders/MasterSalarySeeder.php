<?php

namespace Database\Seeders;

use App\Models\MasterSalary;
use App\Models\Division;
use App\Enums\Position;
use Illuminate\Database\Seeder;

class MasterSalarySeeder extends Seeder
{
    public function run(): void
    {
        $salaryItems = [
            'Base Salary' => [
                Position::MANAGER->value => 15000000,
                Position::LEAD->value => 10000000,
                Position::STAFF->value => 7000000,
            ],
            'Transport Allowance' => [
                Position::MANAGER->value => 1500000,
                Position::LEAD->value => 1000000,
                Position::STAFF->value => 750000,
            ],
        ];

        $divisions = Division::all();

        if ($divisions->isEmpty()) {
            $this->command->warn('Please seed divisions first.');
            return;
        }

        foreach ($divisions as $division) {
            foreach ($salaryItems as $name => $positionData) {
                foreach ($positionData as $position => $nominal) {
                    MasterSalary::updateOrCreate([
                        'division_id' => $division->id,
                        'position' => $position,
                        'name' => $name,
                    ], [
                        'nominal' => $nominal,
                    ]);
                }
            }
        }
    }
}
