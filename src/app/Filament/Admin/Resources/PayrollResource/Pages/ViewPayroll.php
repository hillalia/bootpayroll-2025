<?php

namespace App\Filament\Admin\Resources\PayrollResource\Pages;

use App\Filament\Admin\Resources\PayrollResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPayroll extends ViewRecord
{
    protected static string $resource = PayrollResource::class;

    public function getInfolist(string $name): ?Infolist
    {
        return Infolist::make($this)
            ->record($this->getRecord())
            ->schema([
                Section::make('Employee Info')
                    ->schema([
                        TextEntry::make('employee.user.name')
                            ->label('Employee'),

                        TextEntry::make('employee.division.name')
                            ->label('Division'),

                        TextEntry::make('employee.position')
                            ->label('Position'),
                    ])
                    ->columns(3), // Layout in 3 columns

                Section::make('Salary Period')
                    ->schema([
                        TextEntry::make('period.name')
                            ->label('Period'),

                        TextEntry::make('generated_at')
                            ->label('Generated At')
                            ->dateTime('d M Y H:i'),
                    ])
                    ->columns(2),

                Section::make('Salary Summary')
                    ->schema([
                        TextEntry::make('total_earning')
                            ->label('Total Earning')
                            ->money('IDR', locale: 'id_ID'),

                        TextEntry::make('total_deduction')
                            ->label('Total Deduction')
                            ->money('IDR', locale: 'id_ID'),

                        TextEntry::make('net_salary')
                            ->label('Net Salary')
                            ->money('IDR', locale: 'id_ID'),
                    ])
                    ->columns(3),
            ]);
    }
}
