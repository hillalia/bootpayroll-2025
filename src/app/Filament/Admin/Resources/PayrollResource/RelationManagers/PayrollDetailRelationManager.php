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
            ->headerActions([]) // disable "Create"
            ->actions([])       // disable row actions
            ->bulkActions([]);  // disable bulk delete
    }
}
