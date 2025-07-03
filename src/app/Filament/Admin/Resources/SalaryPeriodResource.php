<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SalaryPeriodResource\Pages;
use App\Models\SalaryPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SalaryPeriodResource extends Resource
{
    protected static ?string $model = SalaryPeriod::class;
    protected static ?string $navigationGroup = 'Manage Payroll';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Salary Periods';
    protected static ?string $pluralModelLabel = 'Salary Periods';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Period Name')
                ->placeholder('e.g. July 2025')
                ->required()
                ->maxLength(50),

            Forms\Components\DatePicker::make('start_date')
                ->label('Start Date')
                ->required(),

            Forms\Components\DatePicker::make('end_date')
                ->label('End Date')
                ->required()
                ->after('start_date'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Period')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('start_date')
                ->label('Start')
                ->date('d M Y')
                ->sortable(),

            Tables\Columns\TextColumn::make('end_date')
                ->label('End')
                ->date('d M Y')
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Created')
                ->dateTime('d M Y, H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                // Optional: Add filters here
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalaryPeriods::route('/'),
            'create' => Pages\CreateSalaryPeriod::route('/create'),
            'edit' => Pages\EditSalaryPeriod::route('/{record}/edit'),
        ];
    }
}
