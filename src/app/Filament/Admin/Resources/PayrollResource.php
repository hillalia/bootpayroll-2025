<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PayrollResource\Pages;
use App\Filament\Admin\Resources\PayrollResource\RelationManagers\PayrollDetailRelationManager;
use App\Models\Payroll;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;
    protected static ?string $navigationGroup = 'Manage Payroll';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Payrolls';
    protected static ?string $pluralModelLabel = 'Payrolls';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Payrolls are auto-generated, not manually edited
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.user.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('period.name')
                    ->label('Period')
                    ->searchable()
                    ->sortable(),

                // Tables\Columns\TextColumn::make('total_earning')
                //     ->label('Total Earning')
                //     ->money('IDR', locale: 'id_ID')
                //     ->sortable(),

                // Tables\Columns\TextColumn::make('total_deduction')
                //     ->label('Total Deduction')
                //     ->money('IDR', locale: 'id_ID')
                //     ->sortable(),

                // Tables\Columns\TextColumn::make('net_salary')
                //     ->label('Net Salary')
                //     ->money('IDR', locale: 'id_ID')
                //     ->sortable(),

                Tables\Columns\TextColumn::make('generated_at')
                    ->label('Generated At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Optional filters (e.g., by period or division)
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PayrollDetailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayrolls::route('/'),
            'view' => Pages\ViewPayroll::route('/{record}'),
        ];
    }
}
