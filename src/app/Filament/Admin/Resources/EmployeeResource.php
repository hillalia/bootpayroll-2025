<?php

namespace App\Filament\Admin\Resources;

use App\Enums\Position;
use App\Filament\Admin\Resources\EmployeeResource\Pages;
use App\Filament\Admin\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationGroup = 'Manage Company';
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Upload avatar inside the related User model
                Forms\Components\Section::make('User Info')
                    ->relationship('user') // this applies to children (i.e. avatar_url)
                    ->schema([
                        Forms\Components\FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->image()
                            ->optimize('webp')
                            ->imageEditor()
                            ->imagePreviewHeight('250')
                            ->panelAspectRatio('7:2')
                            ->panelLayout('integrated')
                            ->columnSpan('full')
                            ->directory('avatars'),
                    ])
                    ->columns(1),

                // Select user (for the employee)
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->required(),

                // Other employee fields
                Forms\Components\Select::make('division_id')
                    ->relationship('division', 'name')
                    ->required(),

                Forms\Components\Select::make('position')
                    ->label('Position')
                    ->options(\App\Enums\Position::options())
                    ->required(),

                Forms\Components\TextInput::make('encrypt_key')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('user.avatar_url')
                    ->defaultImageUrl(url('https://www.gravatar.com/avatar/64e1b8d34f425d19e1ee2ea7236d3028?d=mp&r=g&s=250'))
                    ->label('Avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->formatStateUsing(fn($state) => $state instanceof Position ? $state->label() : $state),
                Tables\Columns\TextColumn::make('encrypt_key')
                    ->label('Key')
                    ->formatStateUsing(fn() => '••••••••••••')
                    ->copyable() // Optional: let admin copy the value
                    ->copyMessage('Key copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('generateKey')
                    ->label('Generate Key')
                    ->icon('heroicon-o-key')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(function ($record) {
                        $record->encrypt_key = Crypt::encryptString(Str::random(16));
                        $record->save();
                        Notification::make()
                            ->success()
                            ->title('Key Regenerated')
                            ->body('Encryption key has been regenerated.')
                            ->send();
                    }),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $pageName = request()->route()?->getName();

        // Jika sedang mengakses halaman create, jangan batasi query
        if (str_contains($pageName, 'create') || str_contains($pageName, 'edit')) {
            return parent::getEloquentQuery();
        }

        if ($user->hasRole(['emp', 'hrd'])) {
            return parent::getEloquentQuery()
                ->where('user_id', $user->id);
        }

        return parent::getEloquentQuery();
    }
}
