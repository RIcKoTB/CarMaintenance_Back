<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Resources\AllBookingsResource\Pages;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

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
                TextColumn::make('user.name')
                    ->label('Клієнт')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service.title')
                    ->label('Послуга')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('booking_date')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('takenBy.name')
                    ->label('Виконавець')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(fn () => Booking::query()
                        ->select('status')
                        ->distinct()
                        ->pluck('status', 'status')
                        ->toArray()
                    )
                    ->native(false),

                SelectFilter::make('taken_by')
                    ->label('Майстер')
                    ->relationship('takenBy', 'name', function ($query) {
                        $query->whereHas('roles', fn($q) => $q->where('name', 'master'));
                    })
                    ->searchable()
                    ->preload()
                    ->native(false),


                SelectFilter::make('user_id')
                    ->label('Клієнт')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('service_id')
                    ->label('Послуга')
                    ->relationship('service', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false),
            ])
            ->defaultSort('booking_date', 'desc');
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllBookings::route('/'),
        ];
    }
}
