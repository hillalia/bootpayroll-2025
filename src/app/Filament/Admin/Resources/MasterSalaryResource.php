<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Position;
use App\Filament\Admin\Resources\MasterSalaryResource\Pages;
use App\Models\Division;
use App\Models\MasterSalary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MasterSalaryResource extends Resource
{
    protected static ?string $model = MasterSalary::class;
    protected static ?string $navigationGroup = 'Manage Payroll';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Master Salaries';
    protected static ?string $pluralModelLabel = 'Master Salaries';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('division_id')
                ->label('Division')
                ->relationship('division', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('position')
                ->label('Position')
                ->options(Position::options())
                ->required()
                ->native(false),

            Forms\Components\TextInput::make('name')
                ->label('Salary Component')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('nominal')
                ->label('Nominal (Rp)')
                ->numeric()
                ->required()
                ->prefix('Rp'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('division.name')
                ->label('Division')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('position')
                ->label('Position')
                ->formatStateUsing(fn($state) => Position::tryFrom($state)?->label() ?? $state)
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Component')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('nominal')
                ->label('Nominal')
                ->money('IDR', locale: 'id_ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime('d M Y, H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                // Optional filters (e.g., by division or position)
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
            'index' => Pages\ListMasterSalaries::route('/'),
            'create' => Pages\CreateMasterSalary::route('/create'),
            'edit' => Pages\EditMasterSalary::route('/{record}/edit'),
        ];
    }
}
