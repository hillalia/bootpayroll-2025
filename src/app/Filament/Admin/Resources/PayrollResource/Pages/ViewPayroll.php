<?php

namespace App\Filament\Admin\Resources\PayrollResource\Pages;

use App\Filament\Admin\Resources\PayrollResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Infolists\Components\TextEntry;

class ViewPayroll extends ViewRecord
{
    protected static string $resource = PayrollResource::class;

    public function getHeaderActions(): array
    {
        return [
            Action::make('unlock')
                ->label('Unlock Payroll')
                ->icon('heroicon-o-lock-open')
                ->modalHeading('Enter Decryption Key')
                ->modalSubheading('Required to view sensitive payroll information.')
                ->form([
                    TextInput::make('decryption_key')
                        ->label('Decryption Key')
                        ->password()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $record = $this->getRecord();
                    $key = $data['decryption_key'];

                    if ($key === $record->employee->encrypt_key) {
                        session()->put("payroll-unlocked-{$record->id}", true);

                        Notification::make()
                            ->success()
                            ->title('Unlocked')
                            ->body('Payroll has been unlocked.')
                            ->send();

                        return redirect()->route('filament.admin.resources.payrolls.view', ['record' => $record->getKey()]);
                    } else {
                        Notification::make()
                            ->danger()
                            ->title('Invalid Key')
                            ->body('The decryption key is incorrect.')
                            ->send();
                    }
                })
                ->visible(fn() => !session()->get("payroll-unlocked-{$this->getRecord()->id}", false)),
        ];
    }

    public function getInfolist(string $name): ?Infolist
    {
        $record = $this->getRecord();
        $isUnlocked = session()->get("payroll-unlocked-{$record->id}", false);

        return Infolist::make($this)
            ->record($record)
            ->schema([
                Section::make('Employee Info')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('employee.user.name')->label('Employee'),
                        TextEntry::make('employee.division.name')->label('Division'),
                        TextEntry::make('employee.position')->label('Position'),
                        TextEntry::make('period.name')->label('Period'),
                        TextEntry::make('generated_at')->label('Generated At')->dateTime('d M Y H:i'),
                    ]),

                Section::make('Salary Info')
                    ->columns(2)
                    ->visible($isUnlocked)
                    ->schema([
                        TextEntry::make('total_earning')->label('Total Earning')->money('IDR'),
                        TextEntry::make('total_deduction')->label('Total Deduction')->money('IDR'),
                        TextEntry::make('net_salary')->label('Net Salary')->money('IDR'),
                    ]),
            ]);
    }
}
