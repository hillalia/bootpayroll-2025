<?php

namespace App\Filament\Admin\Resources\PayrollResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class PayrollDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'details';
    protected static ?string $title = 'Payroll Breakdown';

    public function table(Table $table): Table
    {
        $payroll = $this->getOwnerRecord();
        $isUnlocked = session()->get("payroll-unlocked-{$payroll->id}", false);

        if (! $isUnlocked) {
            return $table
                ->columns([])
                ->emptyStateHeading('🔒 Payroll is locked')
                ->emptyStateDescription('Please unlock this payroll using the button above to view the breakdown.')
                ->emptyStateIcon('heroicon-o-lock-closed')
                ->headerActions([]) // Prevent adding even when unlocked
                ->actions([])
                ->bulkActions([]);
        }

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Component')
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'success' => 'earning',
                        'danger' => 'deduction',
                    ])
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
