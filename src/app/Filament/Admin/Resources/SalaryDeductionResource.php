<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Position;
use App\Filament\Admin\Resources\SalaryDeductionResource\Pages;
use App\Models\SalaryDeduction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SalaryDeductionResource extends Resource
{
    protected static ?string $model = SalaryDeduction::class;
    protected static ?string $navigationGroup = 'Manage Payroll';
    protected static ?string $navigationIcon = 'heroicon-o-percent-badge';
    protected static ?string $navigationLabel = 'Salary Deductions';
    protected static ?string $pluralModelLabel = 'Salary Deductions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('position')
                ->label('Position')
                ->options(Position::options())
                ->required()
                ->native(false),

            Forms\Components\TextInput::make('name')
                ->label('Deduction Name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('persentage')
                ->label('Percentage (%)')
                ->numeric()
                ->default(0)
                ->suffix('%'),

            Forms\Components\TextInput::make('nominal')
                ->label('Nominal (Rp)')
                ->numeric()
                ->default(0)
                ->prefix('Rp'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('position')
                ->label('Position')
                ->formatStateUsing(fn($state) => Position::tryFrom($state)?->label() ?? $state)
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Deduction Name')
                ->searchable(),

            Tables\Columns\TextColumn::make('persentage')
                ->label('Percentage')
                ->suffix('%')
                ->sortable(),

            Tables\Columns\TextColumn::make('nominal')
                ->label('Nominal')
                ->money('IDR', locale: 'id_ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime('d M Y, H:i')
                ->label('Created')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                //
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
            'index' => Pages\ListSalaryDeductions::route('/'),
            'create' => Pages\CreateSalaryDeduction::route('/create'),
            'edit' => Pages\EditSalaryDeduction::route('/{record}/edit'),
        ];
    }
}
