<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Resources\MyBookingsResource\Pages;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;

class MyBookingsResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Мої замовлення';

    public static function canViewNavigation(): bool
    {
        return auth()->check();
    }

    public static function canViewAny(): bool
    {
        return static::canViewNavigation();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn () => Booking::query()->where('taken_by_user_id', auth()->id()))
            ->columns([
                TextColumn::make('service.title')
                    ->label('Послуга')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('booking_date')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
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
            ])
            ->defaultSort('booking_date', 'desc')
            ->actions([
                Action::make('mark_done')
                    ->label('Виконано')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (Booking $record) => $record->status !== 'done')
                    ->action(function (Booking $record, $livewire) {
                        $record->status = 'done';
                        $record->save();

                        Notification::make()
                            ->title('Замовлення виконано')
                            ->success()
                            ->send();

                        $livewire->dispatch('refresh');
                    })
                    ->requiresConfirmation(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyBookings::route('/'),
        ];
    }
}
