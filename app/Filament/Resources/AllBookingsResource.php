<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Resources\AllBookingsResource\Pages;
use Filament\Tables\Columns\TextColumn;

class AllBookingsResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Всі замовлення';

    public static function canViewNavigation(): bool
    {
        return auth()->user()?->hasPermission('bookings') ?? false;
    }
    public static function canViewAny(): bool
    {
        return static::canViewNavigation();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Клієнт'),
                TextColumn::make('service.title')->label('Послуга'),
                TextColumn::make('booking_date')->label('Дата')->dateTime('d.m.Y H:i'),
                TextColumn::make('takenBy.name')->label('Виконавець')->placeholder('-'),
                TextColumn::make('status')->label('Статус')->badge(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllBookings::route('/'),
        ];
    }
}
