<?php

namespace Database\Seeders;

use App\Enums\Position;
use App\Models\SalaryDeduction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalaryDeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deductions = [
            Position::MANAGER->value => [
                ['name' => 'Tax', 'persentage' => '2.5', 'nominal' => '0'],
                ['name' => 'BPJS Class 1', 'persentage' => '0', 'nominal' => '300000'],
            ],
            Position::LEAD->value => [
                ['name' => 'Tax', 'persentage' => '2.5', 'nominal' => '0'],
                ['name' => 'BPJS Class 2', 'persentage' => '0', 'nominal' => '200000'],
            ],
            Position::STAFF->value => [
                ['name' => 'Tax', 'persentage' => '2.5', 'nominal' => '0'],
                ['name' => 'BPJS Class 3', 'persentage' => '0', 'nominal' => '100000'],
            ],
        ];

        foreach ($deductions as $position => $items) {
            foreach ($items as $item) {
                SalaryDeduction::updateOrCreate([
                    'position' => $position,
                    'name' => $item['name'],
                ], [
                    'persentage' => $item['persentage'],
                    'nominal' => $item['nominal'],
                ]);
            }
        }
    }
}
