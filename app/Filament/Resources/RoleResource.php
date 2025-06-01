<?php

namespace App\Filament\Resources;

use App\Models\Role;
use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\ServiceResource;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MultiSelect;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    /**
     * Показувати Roles у навігації лише за правом
     */
    public static function canViewNavigation(): bool
    {
        return auth()->user()?->hasPermission('roles') ?? false;
    }

    /**
     * Показувати список Roles лише за правом
     */
    public static function canViewAny(): bool
    {
        return static::canViewNavigation();
    }

    public static function form(Form $form): Form
    {
        // Явний список ресурсів
        $resources = [
            RoleResource::getSlug()    => RoleResource::getNavigationLabel(),
            UserResource::getSlug()    => UserResource::getNavigationLabel(),
            BookingResource::getSlug()    => BookingResource::getNavigationLabel(),
            ServiceResource::getSlug()    => ServiceResource::getNavigationLabel(),
            StatisticResource::getSlug()    => StatisticResource::getNavigationLabel(),
            AllBookingsResource::getSlug()    => AllBookingsResource::getNavigationLabel(),
            MyBookingsResource::getSlug()    => MyBookingsResource::getNavigationLabel(),
            BookingQueueResource::getSlug()    => BookingQueueResource::getNavigationLabel(),
        ];

        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Назва роли')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),

                MultiSelect::make('permissions')
                    ->label('Доступні сторінки')
                    ->options($resources)
                    ->preload()
                    ->searchable()
                    ->helperText('Оберіть сторінки адмінки, до яких ця роль матиме доступ.')
                    ->required(),
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
                    ->label('Назва роли')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('permissions')
                    ->label('Сторінки')
                    ->formatStateUsing(function ($state): string {
                        // Якщо рядок JSON — декодуємо
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            if (is_array($decoded)) {
                                $state = $decoded;
                            }
                        }
                        // Якщо масив — об'єднуємо
                        if (is_array($state)) {
                            return implode(', ', $state);
                        }
                        return (string) $state;
                    })
                    ->toggleable(),
            ])
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
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
