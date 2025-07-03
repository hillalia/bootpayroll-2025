<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\MasterSalary;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\SalaryDeduction;
use App\Models\SalaryPeriod;
use Illuminate\Support\Facades\DB;

class PayrollGenerator
{
    public function generateForPeriod(SalaryPeriod $period, int $divisionId): int
    {
        $employees = Employee::with(['division'])
            ->where('division_id', $divisionId)
            ->get();

        $count = 0;

        DB::transaction(function () use ($employees, $period, &$count) {
            foreach ($employees as $employee) {
                // Skip if payroll already exists
                if (
                    Payroll::where('employee_id', $employee->id)
                    ->where('salary_period_id', $period->id)
                    ->exists()
                ) {
                    continue;
                }

                $earnings = MasterSalary::where('division_id', $employee->division_id)
                    ->where('position', $employee->position)
                    ->get();

                $deductions = SalaryDeduction::where('position', $employee->position)->get();

                $totalEarning = $earnings->sum(fn($item) => (float) $item->nominal);
                $totalDeduction = $deductions->sum(function ($item) use ($totalEarning) {
                    $percentage = (float) $item->persentage;
                    $nominal = (float) $item->nominal;

                    return $percentage > 0
                        ? $totalEarning * ($percentage / 100)
                        : $nominal;
                });

                $netSalary = $totalEarning - $totalDeduction;

                $payroll = Payroll::create([
                    'employee_id' => $employee->id,
                    'salary_period_id' => $period->id,
                    'total_earning' => $totalEarning,
                    'total_deduction' => $totalDeduction,
                    'net_salary' => $netSalary,
                    'generated_at' => now(),
                ]);

                foreach ($earnings as $earning) {
                    PayrollDetail::create([
                        'payroll_id' => $payroll->id,
                        'name' => $earning->name,
                        'type' => 'earning',
                        'amount' => (float) $earning->nominal,
                    ]);
                }

                foreach ($deductions as $deduction) {
                    $percentage = (float) $deduction->persentage;
                    $nominal = (float) $deduction->nominal;

                    $amount = $percentage > 0
                        ? $totalEarning * ($percentage / 100)
                        : $nominal;

                    PayrollDetail::create([
                        'payroll_id' => $payroll->id,
                        'name' => $deduction->name,
                        'type' => 'deduction',
                        'amount' => $amount,
                    ]);
                }

                $count++;
            }
        });

        return $count;
    }
}
