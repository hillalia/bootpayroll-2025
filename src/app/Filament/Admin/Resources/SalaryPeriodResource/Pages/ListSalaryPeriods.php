<?php

namespace App\Filament\Admin\Resources\SalaryPeriodResource\Pages;

use App\Filament\Admin\Resources\SalaryPeriodResource;
use App\Models\Division;
use App\Models\SalaryPeriod;
use App\Services\PayrollGenerator;
use App\Services\SalaryPeriodGenerator;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSalaryPeriods extends ListRecords
{
    protected static string $resource = SalaryPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
            Action::make('generateSalaryPeriods')
                ->label('Generate Salary Periods')
                ->icon('heroicon-m-calendar-days')
                ->color('primary')
                ->form([
                    Select::make('year')
                        ->label('Tahun')
                        ->options(
                            collect(range(now()->year - 2, now()->year + 2))
                                ->mapWithKeys(fn($y) => [$y => $y])
                        )
                        ->required(),
                ])
                ->action(function (array $data) {
                    $year = $data['year'];

                    try {
                        $count = app(SalaryPeriodGenerator::class)
                            ->generateForYear($year);

                        Notification::make()
                            ->success()
                            ->title('Salary Periods Generated')
                            ->body("Berhasil generate {$count} periode untuk tahun {$year}.")
                            ->persistent()
                            ->duration(5000)
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->danger()
                            ->title('Gagal Generate')
                            ->body("Error: {$e->getMessage()}")
                            ->persistent()
                            ->send();
                    }
                }),

            Action::make('generatePayroll')
                ->label('Generate Payroll')
                ->icon('heroicon-m-currency-dollar')
                ->color('success')
                ->requiresConfirmation()
                ->form([
                    Select::make('division_id')
                        ->label('Division')
                        ->options(Division::pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->reactive(),

                    Select::make('period_id')
                        ->label('Salary Period')
                        ->searchable()
                        ->required()
                        ->options(function (callable $get) {
                            $divisionId = $get('division_id');

                            if (!$divisionId) {
                                return [];
                            }

                            // Only periods that do NOT have payrolls for the division
                            return SalaryPeriod::whereDoesntHave('payrolls', function ($query) use ($divisionId) {
                                $query->whereHas('employee', function ($q) use ($divisionId) {
                                    $q->where('division_id', $divisionId);
                                });
                            })->pluck('name', 'id');
                        })
                        ->reactive()
                        ->disabled(fn(callable $get) => !$get('division_id')),
                ])
                ->action(function (array $data) {
                    $divisionId = $data['division_id'];
                    $periodId = $data['period_id'];

                    try {
                        // ✅ Convert ID to model
                        $period = SalaryPeriod::findOrFail($periodId);

                        $count = app(PayrollGenerator::class)
                            ->generateForPeriod($period, $divisionId);

                        Notification::make()
                            ->success()
                            ->title('Payroll Generated')
                            ->body("Successfully generated payroll for {$count} employee(s).")
                            ->persistent()
                            ->duration(5000)
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->danger()
                            ->title('Generation Failed')
                            ->body("Error: {$e->getMessage()}")
                            ->persistent()
                            ->send();
                    }
                }),
        ];
    }
}
