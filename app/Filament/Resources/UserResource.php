<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\MultiSelect;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';


    /**
     * Чи слід показувати розділ Users у навігації
     */
    public static function canViewNavigation(): bool
    {
        return auth()->user()?->hasPermission('super-admin') ?? false;
    }



    /**
     * Чи може користувач переглядати список
     */
    public static function canViewAny(): bool
    {
        return static::canViewNavigation();
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['id', 'name', 'email', 'roles.name'];
    }
    public static function isGloballySearchable(): bool
    {
        return true;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Ім’я')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('password')
                    ->label('Пароль')
                    ->password()
                    // тільки коли введено — хешуємо й записуємо
                    ->dehydrated(fn($state) => filled($state))
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    // обов’язково на create, на edit — лише якщо ввели
                    ->required(fn(string $context): bool => $context === 'create')
                    ->maxLength(255),

                TextInput::make('password_confirmation')
                    ->label('Підтвердження пароля')
                    ->password()
                    ->dehydrated(false)
                    ->required(fn(string $context): bool => $context === 'create')
                    ->same('password'),


                Toggle::make('is_active')
                    ->label('Активний')
                    ->default(true),

                MultiSelect::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Ролі')
                    ->helperText('Виберіть одну або кілька ролей')
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Ім’я')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Активний'),
                TextColumn::make('roles.name')
                    ->label('Ролі')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i'),
            ])
            ->filters([
                // додайте фільтри за потреби
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
